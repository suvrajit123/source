<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $guarded = [];

    public function user()
    {
    	return $this->belongsTo(User::class)->withDefault();
    }

    public function resource()
    {
    	return $this->belongsTo(Resource::class)->withDefault();
    }
}
