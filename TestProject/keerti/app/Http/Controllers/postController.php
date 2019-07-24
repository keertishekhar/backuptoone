<?php

namespace App\Http\Controllers;
use Auth;
use App\post;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class postController extends Controller
{
     public function index()
    {
        $posts = Post::get();
        return view('/home', compact('posts'));
    }
	
	  public function Add_post(Request $request){


		$this->validate($request, [
			'post' => 'required',
            'postpic' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
        $user = new user();
        $post =new post();
         $post->user()->associate($request->user());
         $post->post = $request->post;
            $postpicName = $post->id.'_postpic'.time().'.'.request()->postpic->getClientOriginalExtension();
            $request->postpic->storeAs('postpics',$postpicName);
            $post->postpic = $postpicName;

        $post->save();

        return back()->with('posts', $post);

    }
}
