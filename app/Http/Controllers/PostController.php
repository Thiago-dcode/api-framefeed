<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    private function setImgShape($image)
    {

        $imageSize = getimagesize($image);

        $imageShape = 'square';

        if ($imageSize[0] > $imageSize[1]) $imageShape = 'horizontal';
        elseif ($imageSize[0] < $imageSize[1]) $imageShape = 'vertical';

        return $imageShape;
    }
    private function createSlug($title, $num = 0)
    {


        $slug = str_replace(' ', '-', strtolower($title));

        if (substr($slug, -1) === '.') {

            $slug = substr($slug, 0, -1);
        }
        if ($num !== 0) {

            $slug = $slug . $num;
        }



        $post = Post::all()->where('slug', $slug)->first();
        if ($post) {

            return $this->createSlug($title, $num + 1);
        }
        return $slug;
    }

    use HttpResponses, HasApiTokens;
    /**
     * Display a listing of the resource.
     */
    public function index()

    {


        $posts = Post::latest()->filter(request()->query())->paginate(9);

        return response()->json($posts);
    }



    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $post = Post::withCount('likes')->where('slug', $slug)->first();
        return response()->json($post);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        // dd($request);

        $fields = $request->validate([
            'title' => 'required|max:100|min:3',
            'image' => 'required|image',
            'body' => 'required|max:2000|min:3',
            'user_id' => 'required',


        ]);

        if ($fields['user_id'] != auth()->user()->id) {
            return response(['message' => 'unauthorized'], 401);
        }

        //creating a unique slug basing on the title.
        $slug = $this->createSlug($fields['title']);
        $fields['slug'] = $slug;
        //creating a excerpt basing on the body field
        $excerpt = substr($fields['body'], 0, round(mb_strlen($fields['body']) * 0.2)) . '...';
        $fields['excerpt'] = $excerpt;

        //Storing the image field on the public folder.
        $fields['image'] = $request->file('image')->store('postImages');
        $imagePath  = public_path() . "/storage/" . $fields['image'];
        $fields['image_shape'] = $this->setImgShape($imagePath);

        $fields['image'] = env('PUBLIC_STORAGE')  . $fields['image'];


        //Setting up the image shape 


        //Creating post

        $post = Post::create($fields);

        //retrieving all ids of the categories choosen
        $categories = explode(",", $request['categories']);
        $categories = Category::all()->whereIn('name', $categories)->pluck('id');

        $post->categories()->attach($categories);



        return response()->json([
            'message' => 'post created successfully',
            'post' => $post

        ]);
    }


    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Post $post, Request $request)
    {

        $fields = $request->validate([
            'title' => 'required|max:100|min:3',
            'image' => 'image',
            'body' => 'required|max:2000|min:3',



        ]);


        //creating a unique slug basing on the title.
        if (strtolower($post->title) !== strtolower($fields['title'])) {

            $slug = $this->createSlug($fields['title']);
            $fields['slug'] = $slug;
        }
        //creating a excerpt basing on the body field
        if ($post->body !== $fields['body']) {
            $excerpt = substr($fields['body'], 0, round(mb_strlen($fields['body']) * 0.2)) . '...';
            $fields['excerpt'] = $excerpt;
        }

        //Storing the image field on the public folder.
        if (isset($fields['image'])) {
            $fields['image'] = $request->file('image')->store('postImages');

            $imagePath  = public_path() . "/storage/" . $fields['image'];
            $fields['image_shape'] = $this->setImgShape($imagePath);

            $fields['image'] = env('PUBLIC_STORAGE') . $fields['image'];
        }
        //Editing post

        $post->update($fields);

        //retrieving all ids of the categories choosen
        $categories = explode(",", $request['categories']);
        $categories = Category::all()->whereIn('name', $categories)->pluck('id');

        $post->categories()->sync($categories);



        return response()->json([
            'message' => 'post edited successfully',
            'post' => $post

        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {

        if ($post) {
            $post->delete();
            return response()->json([
                'message' => 'The post was deleted successfully',
            ]);
        }
    }
}
