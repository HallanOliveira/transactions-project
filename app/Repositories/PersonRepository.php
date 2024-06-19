<?php

namespace App\Repositories;

use App\Models\Person;
use Core\Entities\Person as EntityPerson;
use Core\Entities\Wallet as EntityWallet;
use Core\Ports\PersonRepository as PersonRepositoryInterface;

class PersonRepository implements PersonRepositoryInterface
{
    public function get(int $id, $relations = []): ?EntityPerson
    {
        $person = Person::with($relations)->find($id);
        if (empty($person)) {
            return null;
        }
        $wallet = null;
        if (! empty($person->wallet)) {
            $wallet = new EntityWallet(
                $person->wallet->id,
                $person->wallet->balance,
                $person->wallet->person_id,
                $person->wallet->created_at
            );
        }
        return new EntityPerson(
                $person->id,
                $person->name,
                $person->document_number,
                $person->document_type,
                $person->created_at,
                $wallet
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
