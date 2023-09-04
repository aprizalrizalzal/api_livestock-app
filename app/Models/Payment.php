<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Payment extends Model
{
    use HasApiTokens, HasRoles, HasFactory;

    protected $fillable = [
        'transaction_id',
        'method',
        'date',
        'price',
        'status',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
