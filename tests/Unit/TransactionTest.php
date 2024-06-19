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
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use Core\Ports\UuidGeneratorProvider;
use Core\Ports\DBTransactionProvider;
use App\Adapters\Api\TransactionAuthorizerTrue;
use App\Adapters\Api\TransactionAuthorizerFalse;
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
        $this->personRepository = app(PersonRepository::class);
        $this->useCase          = new TransferBetweenUsers(
            app(PersonRepository::class),
            app(TransactionRepository::class),
            app(WalletRepository::class),
            app(UuidGeneratorProvider::class),
            app(TransactionAuthorizerTrue::class),
            app(DBTransactionFake::class)
        );
    }

    /**
     * @test
     */
    public function test_transfer_success(): void
    {
        $input = new TransactionDTO(
            person_origin_id: 4,
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
        $useCase = new TransferBetweenUsers(
            app(PersonRepository::class),
            app(TransactionRepository::class),
            app(WalletRepository::class),
            app(UuidGeneratorProvider::class),
            app(TransactionAuthorizerFalse::class),
            app(DBTransactionFake::class)
        );

        $input = new TransactionDTO(
            person_origin_id: 4,
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
        $idOrigin = 2;
        $idDest   = 3;

        $walletValueOrigin = $this->personRepository->get($idOrigin)->getWallet()->getBalance();
        $walletValueDest   = $this->personRepository->get($idDest)->getWallet()->getBalance();

        $input = new TransactionDTO(
            person_origin_id: $idOrigin,
            person_destination_id: $idDest,
            type: TransactionTypes::TRANSFER->value,
            amount: $walletValueOrigin - 1
        );

        $useCase = new TransferBetweenUsers(
            app(PersonRepository::class),
            app(TransactionRepository::class),
            app(WalletRepository::class),
            app(UuidGeneratorProvider::class),
            app(TransactionAuthorizerFalse::class),
            app(DBTransactionProvider::class)
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
