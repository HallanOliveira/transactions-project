<?php

namespace Tests\Feature\Integration;

use Tests\TestCase;
use App\Repositories\PersonRepository;
use App\Repositories\WalletRepository;
use Core\Enums\PersonDocumentType;
use App\Adapters\Gateways\TransactionAuthorizerGateway;
use App\Adapters\DBTransactionFake;
use App\Adapters\DBTransactionLaravel;


class TransactionApiTest extends TestCase
{
    private PersonRepository $personRepository;
    private WalletRepository $walletRepository;


    public function setUp(): void
    {
        parent::setUp();
        $this->personRepository = new PersonRepository();
        $this->walletRepository = new WalletRepository();

        $transactionAuthorizerMock = $this->createMock(TransactionAuthorizerGateway::class);
        $transactionAuthorizerMock->method('execute')->willReturn(true);

        $this->app->instance(TransactionAuthorizerGateway::class, $transactionAuthorizerMock);
        $this->app->instance(DBTransactionLaravel::class, app(DBTransactionFake::class));
    }


    /**
     * A basic feature test example.
     */
    public function test_transfer_with_success(): void
    {
        $personId = 1;
        $person   = $this->personRepository->get($personId);
        $person->changeDocumentType(PersonDocumentType::CPF);
        $this->personRepository->save($person);

        $wallet = $person->getWallet();
        $wallet->deposit(100);
        $this->walletRepository->save($wallet);

        $payload  = ["value" => 100,"payer" => $personId, "payee" => 2];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/transfer', $payload);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_transfer_payer_non_existent(): void
    {
        $payload  = ["value" => 100,"payer" => 199,"payee" => 1];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/transfer', $payload);
        $response->assertStatus(404);
    }

    /**
     * A basic feature test example.
     */
    public function test_transfer_payee_non_existent(): void
    {
        $payload  = ["value" => 100,"payer" => 1,"payee" => 199];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/transfer', $payload);
        $response->assertStatus(404);
    }

    /**
     * A basic feature test example.
     */
    public function test_transfer_with_invalid_payload(): void
    {
        $payload  = ["value" => "asdfa","payer" => 1];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/transfer', $payload);
        $response->assertStatus(422);
    }
}
