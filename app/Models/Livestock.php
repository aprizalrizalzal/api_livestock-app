<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'status',
        'detail',
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

    protected function photoUrl (): Attribute
    {
        return Attribute::make(
            get: fn ($photo_url) => asset('/storage/' . $photo_url),
        );
    }

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
