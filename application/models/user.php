<?php

class User extends Eloquent {
	
	public static $table = 'user';

	public static function getByUsername( $username ){
		return User::where_username( $username )->first();
	}
}