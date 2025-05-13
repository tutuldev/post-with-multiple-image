<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
protected $fillable = ['title', 'description', 'codes', 'images','code_titles'];

protected $casts = [
    'images' => 'array',
    'codes' => 'array',
    'code_titles' => 'array',
];


}
