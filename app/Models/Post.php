<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
protected $fillable = ['title', 'description', 'code', 'images'];

protected $casts = [
    'images' => 'array', // JSON হিসেবে handle করবে
];

}
