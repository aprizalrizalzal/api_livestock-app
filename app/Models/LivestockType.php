<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class LivestockType extends Model
{
    use HasApiTokens, HasRoles, HasFactory;

    protected $fillable = [
        'name',
    ];

    public function livestocks()
    {
        return $this->hasMany(Livestock::class);
    }

    public function livestockSpecies()
    {
        return $this->hasMany(LivestockSpecies::class);
    }
}
