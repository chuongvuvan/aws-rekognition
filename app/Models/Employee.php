<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Grananda\AwsFaceMatch\Traits\FacialRecognition;

class Employee extends Model {

    use FacialRecognition;

    protected $table = 'employees';
    protected $fillable = [
        'name',
        'uuid',
        'avatar_image',
    ];
}
