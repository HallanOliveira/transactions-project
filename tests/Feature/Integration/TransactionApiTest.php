<?php

namespace Tests\Feature\Integration;

use Tests\TestCase;
use App\Repositories\PersonRepository;
use Core\Enums\PersonDocumentType;

class TransactionApiTest extends TestCase
{
    private PersonRepository $personRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->personRepository = new PersonRepository();
    }

    /**
     * A basic feature test example.
     */
    public function test_transfer_payer_non_existent(): void
    {
        $payload  = ["value" => 100,"payer" => 24,"payee" => 1];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/transfer', $payload);
        $response->dd();
        $response->assertStatus(404);
    }

    /**
     * A basic feature test example.
     */
    public function test_transfer_payee_non_existent(): void
    {
        $payload  = ["value" => 100,"payer" => 1,"payee" => 26];
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

    /**
     * A basic feature test example.
     */
    // public function test_transfer_with_success(): void
    // {
    //     $personId = 1;
    //     $person   = $this->personRepository->get($personId);
    //     // $person->changeDocumentType(PersonDocumentType::CNPJ);
    //     $this->personRepository->update($person);
    //     dd($person);
    //     $payload  = ["value" => 100,"payer" => $personId, "payee" => 2];
    //     $response = $this->withHeaders([
    //         'Accept' => 'application/json',
    //     ])->post('api/transfer', $payload);
    //     $response->dd();
    //     $response->assertStatus(200);
    // }
}
