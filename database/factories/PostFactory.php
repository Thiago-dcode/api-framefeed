<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        //get all images for factory
        $images = Storage::disk('public')->allFiles('/postFactoryImages');


        //get a random image
        $image = env('PUBLIC_STORAGE') . "/" . $images[rand(0, count($images) - 1)];
     
        //and check his size
        $imageSize = getimagesize($image);

        $imageShape = 'square';

        if ($imageSize[0] > $imageSize[1]) $imageShape = 'horizontal';
        elseif ($imageSize[0] < $imageSize[1]) $imageShape = 'vertical';




        $title = fake()->unique()->sentence();
        $slug = str_replace(' ', '-', strtolower($title));
        $slug = substr($slug, 0, -1);
        return [
            'user_id' => User::all()->random()->id,
            'image' => $image,
            'image_shape' => $imageShape,
            'title' => $title,
            'slug' => $slug,
            'excerpt' => fake()->sentence(),
            'body' => implode('. ', fake()->paragraphs()),
        ];
    }
}
