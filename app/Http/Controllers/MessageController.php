<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use Auth;

class MessageController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
   /**
    *
    * Shows list of all messages
    *
    */
   public function index()
   {
    return redirect()->route('dashboard.index');
   	/*if(Auth::user()->hasRole('user'))
   	{
		$users = User::where('id','<>',Auth::id())->role('admin')->get();
   	}
   	else
   	{
   		$users = User::where('id','<>',Auth::id())->get();
   	}
   	
   	$chats = Message::where('user_from',Auth::id())->orWhere('user_to',Auth::id())->groupBy('chat_id')->orderBy('created_at','desc')->get();
   	$latest = Message::select('user_from','user_to')->where('user_from',Auth::id())->orWhere('user_to',Auth::id())->orderBy('created_at','desc')->limit(1)->first();

   	return view('dashboard.messages.index',compact('users','chats','latest'));*/
   }


   /**
    *
    * Sends a new message 
    * Ajax Handler
    */
   public function sendMessage(Request $request)
   {
   	$user_from 	= User::find($request->user_from);
   	$user_to 	= User::find($request->user_to);

    $check = Message::where(function($q)use($request){
      $q->where('user_from',$request->user_from)->where('user_to',$request->user_to);
    })->orWhere(function($q)use($request){
      $q->where('user_from',$request->user_to)->where('user_to',$request->user_from);
    })->first();
    if($check)
    {
      $chat_id = $check->chat_id;
    }
    else
    {
      $chat_id    = $user_from->username.'_'.$user_to->username;
    }
    
   	

   	if(!blank($request->user_from) AND !blank($request->user_to) AND !blank($request->message))
   	{
   		$message = Message::create([
   			'chat_id'	=>	$chat_id,
   			'user_from'	=>	$request->user_from,
   			'user_to'	=>	$request->user_to,
   			'message'	=>	$request->message
   		]);
   		if($message)
   		{
   			return response()->json(['success'=>'success'],200);
   		}
   		else
   		{
   			return response()->json(['error'=>'error'],200);
   		}
   	}
   	else
   	{
   		return response()->json(['error'=>'error'],200);
   	}
   }

   /**
    *
    * Get a list of all message to and from by user
    * Ajax Handler
    */
   public function getChatHistory(Request $request)
   {
      $this->index();
     /*$messages = Message::where(function($q)use($request){
      $q->where('user_from',$request->user_id)->where('user_to',Auth::id());
     })->orWhere(function($q)use($request){
      $q->where('user_to',$request->user_id)->where('user_from',Auth::id());
     })->orderBy('created_at','asc')->get();
   	 if($messages)
   	 {
   	 	Message::where('user_from',$request->user_id)->orWhere('user_to',$request->user_id)->update(['unread' => '0']);
   	 	return response()->json(['messages'=>$messages],200);
   	 }
   	 else
   	 {
   	 	return response()->json(['error'=>'error'],200);
   	 }*/
   }
   
   
   
}
