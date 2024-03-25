<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PostLike;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    //

    public function index($id)
    {

        $likes = PostLike::where('post_id', $id)->get();

        return response()->json($likes);
    }

    public function store(Request $request)
    {



        $fields = $request->validate([
            'user_id' => 'required',
            'post_id' => 'required'
        ]);


        $like = PostLike::create($fields);
        $likes = PostLike::where('post_id', $request['post_id'])->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'likes' => $likes,
            'like' => $like
        ]);
    }
    public function destroy($id)
    {


        $like = PostLike::where('id', $id)->first();


        if ($like) {
            $postId = $like->post_id;
            $like->delete();
            $likes = PostLike::where('post_id',  $postId)->orderBy('created_at', 'DESC')->get();

            return response()->json($likes);
        }
    }
}
