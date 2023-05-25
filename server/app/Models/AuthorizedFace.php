<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizedFace extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'encoding',
        'is_authorized',
        'img_path'
    ];

    public function access_attempts()
    {
        return $this->hasMany(AccessAttempt::class);
    }
}
