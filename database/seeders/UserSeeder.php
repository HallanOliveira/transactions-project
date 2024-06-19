<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\User;
use App\Models\Wallet;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 25; $i++) {
            $personId = Person::factory()->create()->toArray()['id'];
            User::factory()->create(['person_id'   => $personId]);
            Wallet::factory()->create(['person_id' => $personId]);
        }
    }
}
