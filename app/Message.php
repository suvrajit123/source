<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Message extends Model
{
    public $guarded = [];

    public function userFrom()
    {
    	return $this->belongsTo(User::class,'user_from')->withDefault();
    }

    public function userTo()
    {
    	return $this->belongsTo(User::class,'user_to')->withDefault();
    }

    public function user()
    {
    	if($this->userFrom->id !== Auth::id())
    	{
    		return $this->userFrom;
    	}
    	return $this->userTo;
    }
    
}
