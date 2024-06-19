<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['person_id', 'balance'];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
