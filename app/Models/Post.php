<?php

namespace App\Models;

use Grananda\AwsFaceMatch\Traits\FacialRecognition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, FacialRecognition;

    protected $table = "posts";

    protected $fillable = [
        'title',
        'description',
        'avatar_image'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
