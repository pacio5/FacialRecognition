<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessAttempt extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'authorized',
        'attempted_at',
        'authorized_face_id',
    ];

    public function authorized_face()
    {
        return $this->belongsTo(AuthorizedFace::class);
    }
}
