<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CommentController extends Controller
{

    public function index(Request $request)
    {


        $comments = Comment::latest()->where('post_id', $request['post'])->get();
        return response()->json($comments);
    }

    public function store(Request $request)
    {


        $fields = $request->validate([
            'user_id' => 'required',
            'post_id' => 'required',
            'comment' => 'required|min:3|max:1000',
        ]);


            Comment::create($fields);
            $comments = Comment::where('post_id', $request['post_id'])->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'message' => 'Comment created successfully',
            'comments' => $comments
        ]);
    }
    public function destroy($id)
    {

        $comment = Comment::where('id', $id)->first();
      
        if ($comment) {
            $postId = $comment->post_id;
            $comment->delete();
            $comments = Comment::where('post_id', $postId)->orderBy('created_at', 'DESC')->get();
            return response()->json([
                'message' => 'The comment was deleted successfully',
                'comments' => $comments
            ]);
        }
    }
}
