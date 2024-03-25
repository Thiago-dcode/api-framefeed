<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Post extends Model
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];
    protected $with = ['author','categories','likes'];
    public function scopeFilter($query, array $filters)
    {



        $query->when(
            $filters['search'] ?? false,
            fn ($query, $search) =>
            $query->where(fn ($query) =>
            $query->where('title', 'like', "%" . $search . "%")
                ->orWhere('body', 'like', "%" . $search . "%"))

        );
    
        $query->when(
            $filters['category'] ?? false,
            function ($query, $categories) {
                $query->whereHas('categories', function ($query) use ($categories) {
                    $query->whereIn('name', $categories);
                }, '=', count($categories));
            }

        );
    }

    public function categories()
    {

        return $this->belongsToMany(Category::class);
    }
    public function author()
    {

        return $this->belongsTo(User::class, 'user_id');
    }
    public function comments()
    {

        return $this->hasMany(Comment::class);
    }

    public function likes()
    {

        return $this->hasMany(PostLike::class);
    }
}
