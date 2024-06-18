<?php

namespace Tests\Feature\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_transfer_payer_non_existent(): void
    {
        $payload  = ["value" => 100,"payer" => 26,"payee" => 1];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/transfer', $payload);
        $response->assertStatus(404);
    }

    public function test_transfer_payee_non_existent(): void
    {
        $payload  = ["value" => 100,"payer" => 1,"payee" => 26];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/transfer', $payload);
        $response->assertStatus(404);
    }
}
