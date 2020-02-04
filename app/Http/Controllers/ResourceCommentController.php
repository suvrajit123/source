<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Auth;

class ResourceCommentController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function store(Request $request)
    {
    	$request->validate(['comment_text'=>'required']);
    	$comment = Comment::create([
    		'user_id'		=>	Auth::id(),
    		'resource_id' 	=>  $request->resource_id,
    		'comment'		=>	$request->comment_text
    	]);
    	return redirect()->back();
    }

    public function destroy(Comment $comment)
    {
    	$comment->delete();
    	return redirect()->back();
    }
}
