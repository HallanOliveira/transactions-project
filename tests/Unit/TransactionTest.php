<?php

namespace Tests\Unit;

use Tests\TestCase;
use Core\UseCases\TransferBetweenUsers;
use Core\DTOs\TransactionDTO;
use Core\Enums\TransactionTypes;
use Core\Enums\PersonDocumentType;
use Core\Exceptions\PersonTypeInvalidException;
use Core\Exceptions\InsufficientBalanceException;
use Core\Exceptions\TransactionFailedException;
use App\Repositories\PersonRepository;
use App\Adapters\Api\TransactionAuthorizer;
use App\Adapters\DBTransactionLaravel;
use App\Adapters\DBTransactionFake;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class TransactionTest extends TestCase
{
    use DatabaseTransactions;

    private TransferBetweenUsers $useCase;
    private PersonRepository     $personRepository;

    public function setUp(): void
    {
        parent::setUp();
        $transactionAuthorizerMock = $this->createMock(TransactionAuthorizer::class);
        $transactionAuthorizerMock->method('execute')->willReturn(true);

        $this->app->instance(TransactionAuthorizer::class, $transactionAuthorizerMock);
        $this->app->instance(DBTransactionLaravel::class, app(DBTransactionFake::class));

        $this->personRepository = app(PersonRepository::class);
        $this->useCase          = app(TransferBetweenUsers::class);
    }

    /**
     * @test
     */
    public function test_transfer_success(): void
    {
        $personId = 5;
        $personEntity = $this->personRepository->get($personId);
        $personEntity->changeDocumentType(PersonDocumentType::CPF);
        $this->personRepository->save($personEntity);

        $input = new TransactionDTO(
            person_origin_id: $personId,
            person_destination_id: 2,
            type: TransactionTypes::TRANSFER->value,
            amount: 10
        );
        $output = $this->useCase->execute($input);
        $this->assertInstanceOf(TransactionDTO::class, $output);
    }

    /**
     * @test
     */
    public function test_transfer_with_user_type_dealer(): void
    {
        $personId     = 1;
        $personEntity = $this->personRepository->get($personId);
        $personEntity->changeDocumentType(PersonDocumentType::CNPJ);
        $this->personRepository->save($personEntity);

        $input = new TransactionDTO(
            person_origin_id: $personId,
            person_destination_id: 2,
            type: TransactionTypes::TRANSFER->value,
            amount: 10
        );
        $this->expectException(PersonTypeInvalidException::class);
        $this->useCase->execute($input);
        $this->fail('Payer cannot perform transactions, because it is a dealer.');
    }

    /**
     * @test
     */
    public function test_transfer_with_insuficient_balance(): void
    {
        $personId     = 2;
        $personEntity = $this->personRepository->get($personId);
        $personEntity->changeDocumentType(PersonDocumentType::CPF);
        $this->personRepository->save($personEntity);
        $balance      = $personEntity->getWallet()->getBalance();

        $input = new TransactionDTO(
            person_origin_id: $personId,
            person_destination_id: 2,
            type: TransactionTypes::TRANSFER->value,
            amount: $balance + 1
        );
        $this->expectException(InsufficientBalanceException::class);
        $this->useCase->execute($input);
        $this->fail('Wallet with insufficient balance dont should be able to transfer.');
    }

    /**
     * @test
     */
    public function test_transfer_with_transaction_authorizer_false(): void
    {
        $transactionAuthorizerMock = $this->createMock(TransactionAuthorizer::class);
        $transactionAuthorizerMock->method('execute')->willReturn(false);
        $this->app->instance(TransactionAuthorizer::class, $transactionAuthorizerMock);

        $useCase      = app(TransferBetweenUsers::class);
        $personId     = 4;
        $personEntity = $this->personRepository->get($personId);
        $personEntity->changeDocumentType(PersonDocumentType::CPF);
        $this->personRepository->save($personEntity);

        $input = new TransactionDTO(
            person_origin_id: $personId,
            person_destination_id: 2,
            type: TransactionTypes::TRANSFER->value,
            amount: 10
        );
        $this->expectException(TransactionFailedException::class);
        $useCase->execute($input);
        $this->fail('Transaction should not be authorized.');
    }

    /**
     * @test
     */
    public function test_transfer_integrity_with_proccess_failed_in_middle(): void
    {
        $transactionAuthorizerMock = $this->createMock(TransactionAuthorizer::class);
        $transactionAuthorizerMock->method('execute')->willReturn(false);
        $this->app->instance(TransactionAuthorizer::class, $transactionAuthorizerMock);

        $useCase      = app(TransferBetweenUsers::class);
        $idOrigin     = 2;
        $idDest       = 3;
        $personEntity = $this->personRepository->get($idOrigin);
        $personEntity->changeDocumentType(PersonDocumentType::CPF);
        $this->personRepository->save($personEntity);

        $walletValueOrigin = $this->personRepository->get($idOrigin)->getWallet()->getBalance();
        $walletValueDest   = $this->personRepository->get($idDest)->getWallet()->getBalance();

        $input = new TransactionDTO(
            person_origin_id: $idOrigin,
            person_destination_id: $idDest,
            type: TransactionTypes::TRANSFER->value,
            amount: $walletValueOrigin - 1
        );

        try {
            $useCase->execute($input);
        } catch (\Exception $e) {
            $walletValueOriginAfter = $this->personRepository->get($idOrigin)->getWallet()->getBalance();
            $walletValueDestAfter   = $this->personRepository->get($idDest)->getWallet()->getBalance();
            $this->assertEquals($walletValueOrigin, $walletValueOriginAfter);
            $this->assertEquals($walletValueDest, $walletValueDestAfter);
            return;
        }
        $this->fail('Transaction should not be completed.');
    }
}
