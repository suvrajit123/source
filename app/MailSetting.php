<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailSetting extends Model
{
    protected $table = "mail_settings";

    protected $guarded = [];


    public static function getSettings($name){
    	try {
    		$val = self::where('name', $name)->first()->value;
    		return $val;
    	} catch (\Exception $e) {
    		return null;
    	}
    }
}
