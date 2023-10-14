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
        'date',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function getPriceAttribute($value)
    {
        return (float) $value;
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (float) $value;
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
