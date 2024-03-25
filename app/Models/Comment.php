<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory,HasFactory;
    protected $with = ['author', 'likes'];

    protected $guarded = [];

    public function author()
    {

        return $this->belongsTo(User::class, 'user_id');
    }
    public function post()
    {

        return $this->belongsTo(Post::class);
    }
    public function likes()
    {

        return $this->hasMany(CommentLike::class);
    }
}
