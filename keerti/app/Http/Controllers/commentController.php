<?php

namespace App\Http\Controllers;
use Auth;
use App\Post;
use App\Comment;
use Illuminate\Http\Request;

class commentController extends Controller
{
    public function index()
    {
        $comments = Comment::get();
        $posts = Post::get();
        return view('/home', compact('comments', 'posts'));
    }
	
	  public function Add_comment(Request $request){


		$this->validate($request, [
			'comment' => 'required',
            
        ]);
            $comment = new Comment();
                $comment->user_id = $request->user_id;
                $comment->post_id = $request->post_id;
                $comment->comment = $request->comment;
                $comment->save();
              return back()->with('comments', $comment);
             
    }
}
