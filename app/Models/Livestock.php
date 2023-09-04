<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Livestock extends Model
{
    use HasApiTokens, HasRoles, HasFactory;

    protected $fillable = [
        'profile_id',
        'photo_url',
        'livestock_type_id',
        'livestock_species_id',
        'age',
        'gender',
        'price',
        'sold',
        'detail',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function livestockPhotos()
    {
        return $this->hasMany(LivestockPhoto::class);
    }

    public function livestockType()
    {
        return $this->belongsTo(LivestockType::class);
    }

    public function livestockSpecies()
    {
        return $this->belongsTo(LivestockSpecies::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
