<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class LivestockSpecies extends Model
{
    use HasApiTokens, HasRoles, HasFactory;

    protected $fillable = [
        'livestock_type_id',
        'name',
    ];

    public function livestocks()
    {
        return $this->hasMany(Livestock::class);
    }

    public function livestockType()
    {
        return $this->belongsTo(LivestockType::class);
    }
}
