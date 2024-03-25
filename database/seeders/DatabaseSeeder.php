<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\PostLike;
use Database\Factories\CommentLikeFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(50)->create();

        foreach ($users as $user) {
            $user->update(['avatar' => 'https://i.pravatar.cc/40?u=' . $user->id]);
        }
        $posts = Post::factory(100)->create();

        //categories:
        $categoriesToCreate = ['landscape', 'portrait', 'aerial', 'cityscape', 'street', 'fauna', 'flora', 'flowers', 'people', 'abstract', 'lifestyle', 'commercial', 'adventure', 'aesthetic', 'architectural', 'astrophotography', 'automtive', 'black&white', 'conceptual', 'contemporary', 'documentary', 'film', 'fantasy', 'analog', 'fine-art', 'food', 'macro', 'minimalist', 'monochrome', 'urbex', 'long-exposure', 'pet', 'photojournalism', 'product', 'real-state', 'seascape', 'self-portrait', 'winter', 'summer', 'spring', 'autumn', 'sports', 'travel', 'underwater', 'weather', 'wildlife', 'snow'];


        foreach ($categoriesToCreate as $category) {

            Category::create(['name' => $category]);
        }


        foreach ($posts as  $post) {

            //seeding pivot table
            $post->categories()->attach(Category::all()->random(rand(2, 4))->unique()->pluck('id')->toArray());

            //seeding post like table
            PostLike::factory(rand(10, 60))->create([
                'post_id' => $post->id,
            ]);

            //Seeding comment for each post
            $comments =  Comment::factory(rand(2, 5))->create([
                'post_id' => $post->id,
            ]);
            //seeding comment like
            foreach ($comments as $comment) {
                CommentLike::factory(rand(0, 10))->create([
                    'comment_id' => $comment->id
                ]);
            }
        }
    }
}
