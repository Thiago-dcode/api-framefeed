<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory, HasFactory;
    protected $guarded = [];

    public function author()
    {

        return $this->belongsTo(User::class, 'user_id');
    }
    public function post()
    {

        return $this->belongsTo(Post::class);
    }
}
