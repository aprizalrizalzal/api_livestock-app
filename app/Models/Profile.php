<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Profile extends Model
{
    use HasApiTokens, HasRoles, HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'photo_url',
        'name',
        'gender',
        'phone_number',
        'address',
    ];

    protected function photoUrl (): Attribute
    {
        return Attribute::make(
            get: fn ($photo_url) => asset('/storage/' . $photo_url),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livestocks()
    {
        return $this->hasMany(Livestock::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
