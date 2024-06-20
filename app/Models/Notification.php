<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'type',
        'message',
        'is_sent',
        'sent_at',
        'created_at'
    ];
}
