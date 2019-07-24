<?php

namespace App\Http\Controllers;
use Auth;
use App\post;
use App\User;
use App\Comment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class postController extends Controller
{
     public function index()
    {
        $posts = Post::get();
        $comments = Comment::get();
        return view('/home', compact('posts', 'comments'));
    }
	
	  public function Add_post(Request $request){


		$this->validate($request, [
			'post' => 'required',
            
		]);
        $user = new user();
        $post =new post();
         
         
           if($request->postpic == null){
               
               $post->user()->associate($request->user());
                $post->post = $request->post;
               $post->postpic = $request->postpic;
               $post->save();
               return back()->with('posts', $post);
               
           }else{
            $post->user()->associate($request->user());
            $post->post = $request->post;
            $postpicName = $post->id.'_postpic'.time().'.'.request()->postpic->getClientOriginalExtension();
            $request->postpic->storeAs('postpics',$postpicName);
            $post->postpic = $postpicName;
            $post->save();
            return back()->with('posts', $post);
           }
        

        

    }
}
