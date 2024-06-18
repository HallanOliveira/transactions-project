<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';

    protected $fillable = [
        'name',
        'document_number',
        'document_type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
