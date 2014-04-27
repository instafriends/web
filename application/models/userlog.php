<?php

class Userlog extends Eloquent {
	
	public static $table = 'userlog';

	public static function getLastLog( $userid ){
		return Userlog::where( 'userid', '=',  $userid)->order_by('id', 'desc')->first();
	}
}