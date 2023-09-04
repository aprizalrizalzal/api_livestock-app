<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class LivestockPhoto extends Model
{
    use HasApiTokens, HasRoles, HasFactory;

    protected $fillable = [
        'livestock_id',
        'photo_url',
    ];

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }
}
