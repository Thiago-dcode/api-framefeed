<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CommentLike;
use Illuminate\Http\Request;

class CommentLikeController extends Controller
{

    public function index($id)
    {

        $likes = CommentLike::where('comment_id', $id)->get();

        return response()->json($likes);
    }

    public function store(Request $request)
    {


        $fields = $request->validate([
            'user_id' => 'required',
            'comment_id' => 'required'
        ]);


        $like = CommentLike::create($fields);
        $likes = CommentLike::where('comment_id', $request['comment_id'])->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'likes' => $likes,
            'like' => $like
        ]);
    }
    public function destroy($id)
    {

        $like = CommentLike::where('id',$id)->first();
       
    
        if ($like) {
            $commentId = $like->comment_id;
            $like->delete();
            $likes = CommentLike::where('comment_id',  $commentId)->get();
          
            return response()->json($likes);
        }
    }
}
