<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Transaction extends Model
{
    use HasApiTokens, HasRoles, HasFactory;

    protected $fillable = [
        'profile_id',
        'livestock_id',
        'date',
        'status',
        'method',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
