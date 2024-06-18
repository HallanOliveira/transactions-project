<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_origin_id',
        'type',
        'amount',
        'person_destination_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
