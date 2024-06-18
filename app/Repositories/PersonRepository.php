<?php

namespace App\Repositories;

use App\Models\Person;
use Core\Entities\Person as EntityPerson;
use Core\Ports\PersonRepository as PersonRepositoryInterface;

class PersonRepository implements PersonRepositoryInterface
{
    public function get(int $id): ?EntityPerson
    {
        $person = Person::find($id);
        if (empty($person)) {
            return null;
        }
        return new EntityPerson(
            $person->id,
            $person->name,
            $person->document_number,
            $person->document_type,
            $person->created_at
        );
    }

    public function update(EntityPerson $person): bool
    {
        return Person::query()->update([
            'id'              => $person->id,
            'name'            => $person->name,
            'document_number' => $person->document_number,
            'document_type'   => $person->document_type,
        ]);
    }
}
