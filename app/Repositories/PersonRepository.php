<?php

namespace App\Repositories;

use App\Models\Person;
use Core\Entities\Person as EntityPerson;
use Core\Entities\Wallet as EntityWallet;
use Core\Ports\PersonRepository as PersonRepositoryInterface;

class PersonRepository implements PersonRepositoryInterface
{
    public function get(int $idPerson, $relations = []): ?EntityPerson
    {
        $person = Person::with($relations)->find($idPerson);
        if (empty($person)) {
            return null;
        }
        $wallet = null;
        if (! empty($person->wallet)) {
            $wallet = new EntityWallet(
                id: $person->wallet->id,
                balance: $person->wallet->balance,
                person_id: $person->wallet->person_id,
                created_at: $person->wallet->created_at
            );
        }
        return new EntityPerson(
            id: $person->id,
            name: $person->name,
            document_number: $person->document_number,
            document_type: $person->document_type,
            phone: $person->phone,
            created_at: $person->created_at,
            wallet: $wallet
        );
    }

    public function save(EntityPerson $person): bool
    {
        return Person::query()->upsert([
            'id'              => $person->getId(),
            'name'            => $person->getName(),
            'document_number' => $person->getDocumentNumber(),
            'document_type'   => $person->getDocumentType(),
        ],'id');
    }
}
