<?php

require_once( 'instagram/InstagramConnect.php' );

class Instafriends{

	// Connect params
	private $access;

	// InstagramConnect
	private $instagramConnect;

	// Max requests
	private $maxRequests = 1500;

	// Construct
	public function __construct(){
		$this->access = array(
				'client_id' 	=> Config::get( 'instagram.client_id' ),
				'client_secret' => Config::get( 'instagram.client_secret' ),
				'grant_type' 	=> Config::get( 'instagram.grant_type' ),
				'redirect_uri' 	=> Config::get( 'instagram.redirect_uri' ),
			);
			$this->instagramConnect = new InstagramConnect( $this->access );
	}

	// Authorize user
	public function authorizeUser(){
		if( Session::has( 'code' ) ){
			$this->instagramConnect->setAccessToken( Session::get( 'code' ) );
			$user = $this->getCurrentUser();
			if( $user ){
				$id = InstafriendsDB::registerUser( $user );
				Session::put( 'userIGid', $user->id );
				Session::put( 'userIFid', $id );
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	// Get current userIGid
	public function getUserIGId(){
		if( Session::has( 'userIGid' ) ){
			return Session::get( 'userIGid' );
		} else {
			return false;
		}
	}

	// Redirect the user to Instagram authorization URL
	public function getAuthorizationURL(){
		return Redirect::to( $this->instagramConnect->openAuthorizationUrl() );
	}

	// Return if the user is logged at Instagram
	public function isLogged(){
		return Session::has( 'code' ) && $this->getUserIGId();
	}

	// Unfollow user
	public function unfollowUser( $id ){
		$data = InstafriendsDB::getUserData( Session::get( 'userIFid' ) );
		$new = array();
		$friend_data = array();
		$itwas = false;
		$users = isset( $data[ 'fans' ] ) ? $data[ 'fans' ] : array();
		if( count( $users ) > 0 )
		foreach( $users as $user ){
			if( $user['id'] ==  $id){ $friend_data = $user; $itwas = 'fans'; continue; }
			$new[] = $user;
		}
		$data[ 'fans' ] = $new;

		$users = isset( $data[ 'friends' ] ) ? $data[ 'friends' ] : array();
		if( count( $users ) > 0 )
		foreach( $users as $user ){
			if( $user['id'] ==  $id){ $friend_data = $user; $itwas = 'friends'; continue; }
			$new[] = $user;
		}
		$data[ 'friends' ] = $new;

		$data[ 'count' ][ 'follows' ]--;

		if( $itwas == 'friends' ){
			$data[ 'stalkers' ][] = $friend_data;
			$data[ 'count'][ 'friends' ]--;
			$data[ 'count' ][ 'stalkers']++;
		}
		if( $itwas == 'fans' ){
			$data[ 'count'][ 'fans' ]--;
		}
		$key = 'userinfo' . $id;
		InstafriendsUserInfo::removeItem( $key );
		InstafriendsDB::updateUserData( $data );
		return $this->instagramConnect->modifyUserRelationship( $id, 'unfollow' );
	}
	
	// Follow user
	public function followUser( $id ){
		$data = InstafriendsDB::getUserData( Session::get( 'userIFid' ) );
		$new = array();
		$friend_data = array();
		$itwas = false;
		$users = isset( $data[ 'stalkers' ] ) ? $data[ 'stalkers' ] : array();
		if( count( $users ) > 0 )
		foreach( $users as $user ){
			if( $user['id'] ==  $id){ $friend_data = $user; $itwas = 'stalkers'; continue; }
			$new[] = $user;
		}
		$data[ 'stalkers' ] = $new;
		if( $itwas == 'stalkers' ){
			$data['friends'][] = $friend_data;
			$data[ 'count' ][ 'stalkers']--;
			$data[ 'count'][ 'friends' ]++;
		}
		$key = 'userinfo' . $id;
		InstafriendsUserInfo::removeItem( $key );
		InstafriendsDB::updateUserData( $data );
		return $this->instagramConnect->modifyUserRelationship( $id, 'follow' );
	}

	// Get user information
	public function getUserInfo( $id ){
		$key = 'userinfo' . $id;
		$userInfo = InstafriendsUserInfo::getItem( $key );
		if( $userInfo ){ return $userInfo; }
		$userInfo = json_decode( $this->instagramConnect->getUser( $id ) );
		if( isset( $userInfo->meta ) && $userInfo->meta->code == 200 ){
			$relationship = json_decode( $this->instagramConnect->getUserRelationship( $id ) );
			if( isset( $relationship->meta ) && $relationship->meta->code == 200 ){
				$userInfo->relationship = array();
				$userInfo->relationship[ 'outgoing_status' ] = $relationship->data->outgoing_status;
				$userInfo->relationship[ 'incoming_status' ] = $relationship->data->incoming_status;
			}
			InstafriendsUserInfo::addItem( $key, $userInfo );
			return $userInfo;
		} else {
			return false;
		}
	}

	// Get the current user
	public function getCurrentUser(){
		return $this->instagramConnect->getCurrentUser();
	}

	// Logout
	public function logout(){
		return Session::flush();
	}

	// Check if it is needed to reprocess the user information
	private function shouldIReprocessUserData(){
		if( Session::has( 'shouldIReprocessUserDataVerifield' ) ){ return false;}
		Session::put( 'shouldIReprocessUserDataVerifield', true );
		$data = InstafriendsDB::getUserData( Session::get( 'userIFid' ) );
		if( $data == '' ){ 
			return true; 
		}
		$user = $this->getCurrentUserInfo();
		if( $user[ 'media' ] != $data[ 'count' ][ 'media' ] || $user[ 'followed_by' ] != $data[ 'count' ][ 'followed_by' ] || $user[ 'follows' ] != $data[ 'count' ][ 'follows' ] ){
			return true;
		}
		return false;
	}

	public function getMediaRecentByUser( $id ){
		$medias = json_decode($this->instagramConnect->getUserRecent($id));
 		$data = $medias->data;
		$photos = array();
		foreach( $data  as $media ){
			$photos[] = array( 
						'thumbnail' => $media->images->thumbnail->url,
						'link' =>  $media->link,
						'caption' => ( $media->caption != NULL ) ? $media->caption->text : ''
						);
		}
		return $photos;
	}

	public function dashboard(){
		if( $this->shouldIReprocessUserData() ){ $this->getAndProcessDataFromIG();}
		return $this->getFriendsNumbers();
		//$this->getAndProcessDataFromIG();
	}

	private function getFriendsNumbers(){
		return array(
			'fans' 		=> count( $this->getFans() ),
			'friends' 	=> count( $this->getFriends() ),
			'stalkers' 	=> count( $this->getStalkers() ),
		);
	}

	private function getDataFromIF( $key ){
		$data = InstafriendsDB::getUserData( Session::get( 'userIFid' ) );
		return $data[ $key ];
	}

	public function getFans(){ return $this->getDataFromIF( 'fans' ); }
	public function getFriends(){ return $this->getDataFromIF( 'friends' ); }
	public function getStalkers(){ return $this->getDataFromIF( 'stalkers' ); }


	// Get all friends, follows and fans from IG API and save the data at the DB
	public function getAndProcessDataFromIG(){
		$follows  = $this->getAllFollowsFromIG();
		$followed = $this->getAllFollowedByFromIG();

		if( !$follows && !$followed ){ return false; }

		$follows  = InstafriendsUtil::processUsersArray( $follows );
		$followed = InstafriendsUtil::processUsersArray( $followed );

		$friends  = array(); 	// when the user follows and is followed back
		$fans 	  = array();	// when the user follow and is not followed back
		$stalkers = array();	// when the user is followed and do not follows back

		foreach ($follows as $data) {
			if( isset( $followed[$data['username'] ] ) ){
				$friends[$data['username']] = $data;
			} else {
				$fans[$data['username']] = $data;
			}
		}
		foreach ($followed as $data) {
			if( isset( $follows[$data['username']] ) ){
				$friends[$data['username']] = $data;
			} else {
				$stalkers[$data['username']] = $data;
			}
		}

		$userlog = $this->getCurrentUserInfo();

		$userlog['fans'] 		= count( $fans );
		$userlog['friends'] 	= count( $friends );
		$userlog['stalkers'] 	= count( $stalkers );

		InstafriendsDB::registerUserLog( $userlog );

		$userdata = array();
		$userdata[ 'id' ] 	 	= Session::get( 'userIFid' );
		$userdata[ 'count' ] 	= array( 
											'follows' 	  => $userlog[ 'follows' ],
											'followed_by' => $userlog[ 'followed_by' ], 
											'media' 	  => $userlog[ 'media' ], 
											'fans' 		  => count( $fans ), 
											'friends' 	  => count( $friends ), 
											'stalkers' 	  => count( $stalkers ) 
										);
		$userdata[ 'fans' ] 	= $fans;
		$userdata[ 'friends' ] 	= $friends;
		$userdata[ 'stalkers' ] = $stalkers;
		InstafriendsDB::updateUserData( $userdata );
	}

	public function getCurrentUserInfo(){
		$user 		 = $this->getUserInfo( $this->getUserIGId() );
		$userlog 	 = array();
		$userlog['userid'] 		= Session::get( 'userIFid' );
		$userlog['follows'] 	= $user->data->counts->follows;
		$userlog['followed_by'] = $user->data->counts->followed_by;
		$userlog['media'] 		= $user->data->counts->media;
		$userlog[ 'info' ]		= $user;
		return $userlog;
	}

	// Get all users that follow the current one from IG API
	private function getAllFollowsFromIG(){
		$response = $this->instagramConnect->getUserFollows( $this->getUserIGId() );
		$follows = array();
		$response = json_decode( $response, true );
		$count = 1;
		while( true ){
			$follows = array_merge( $follows, $response[ 'data' ] );
			if( isset( $response[ 'pagination' ] ) && isset( $response[ 'pagination' ][ 'next_url' ] ) ){
				$response = $this->instagramConnect->getByCursor( $response[ 'pagination' ][ 'next_url' ] );
				$response = json_decode( $response, true );
				if( $count > $this->maxRequests ){
					die( ' getAllFollows::maxRequests ' );
				}
				$count++;
			} else {
				break;
			}
		}
		return $follows;
	}

	// Get all users that are followed by the current one from IG API
	private function getAllFollowedByFromIG(){
		$response = $this->instagramConnect->getUserFollowedBy( $this->getUserIGId() );
		$followed = array();
		$response = json_decode( $response, true );
		$count = 1;
		while(true){
			$followed = array_merge($followed, $response[ 'data' ]);
			if( isset( $response[ 'pagination' ] ) && isset( $response[ 'pagination' ][ 'next_url' ]) ){
				$response = $this->instagramConnect->getByCursor( $response[ 'pagination' ][ 'next_url' ] );
				$response = json_decode( $response, true );
				if( $count > $this->maxRequests ){
					die( ' getFollowing::maxRequests ' );
				}
				$count++;
			} else {
				break;
			}
		}
		return $followed;
	}
}

class InstafriendsDB{
	public static function registerUser( $data ){
		$user = User::getByUsername( $data->username );
		if( !$user ){
			$user = new User();
			$user->username = $data->username;
			$user->userIGId = $data->id;
			$user->data 	= '';
			$user->save();
		}
		return $user->id;
	}

	public static function updateUserData( $data ){
		$user = User::find( $data[ 'id' ] );
		$user->data = base64_encode( serialize( $data ) );
		$user->save();
	}

	public static function getUserData( $id ){
		$user = User::find( $id );
		if( $user  ){
			$data = @unserialize( base64_decode( $user->data ) );
			if( $data ){
				return $data;	
			} else {
				return false;
			}
			
		} else {
			return false;
		}
		
	}

	public static function registerUserLog( $data ){
		$userLog = new UserLog();
		$userLog->userid	  = $data[ 'userid' ];
		$userLog->follows 	  = $data[ 'follows' ];
		$userLog->followed_by = $data[ 'followed_by' ];
		$userLog->media 	  = $data[ 'media' ];
		$userLog->fans 	 	  = $data[ 'fans' ];
		$userLog->friends	  = $data[ 'friends' ];
		$userLog->stalkers 	  = $data[ 'stalkers' ];
		$userLog->ip 		  = $_SERVER['REMOTE_ADDR'];
		if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod')){
			$userLog->device 	  = 'm';	
		} else {
			$userLog->device 	  = 'd';
		}
		
		$userLog->save();
	}
}

class InstafriendsUserInfo{

	public static function addItem( $key, $value ){
		$elements = Session::get( 'instafriends' );
		if( !$elements ){ $elements = array(); }
		$elements[ $key ] = base64_encode( serialize( $value ) );
		Session::put( 'instafriends', $elements );
		return true;
	}

	public static function getItem( $key ){
		$elements = Session::get( 'instafriends' );
		if( !$elements ){ return false; }
		if( isset( $elements[ $key ] ) ){
			return unserialize( base64_decode( $elements[ $key ] ) );
		}
		return false;
	}
	public static function removeItem( $key ){
		$elements = Session::get( 'instafriends' );
		if( !$elements ){ return false; }
		if( isset( $elements[ $key ] ) ){
			unset( $elements[ $key ] );
			Session::put( 'instafriends', $elements );
		}
		return false;
	}

}

class InstafriendsPagination{

	public static $resultsPerPage = 25;

	public static function paginate( $list ){
		sort( $list );
		$page = Input::get('page', 1);
		$ini = ( $page - 1 ) * static::$resultsPerPage;
		$end = ( $ini + static::$resultsPerPage ) >  count( $list ) ? count( $list ) : $ini + static::$resultsPerPage;
		$end = $end - 1;
		$i = 0;
		$users = array();
		foreach( $list as $user ){
			if( $i >= $ini && $i <= $end ){
				$users[] = $user;
			}
			$i++;
		}
		$total = ceil( count( $list ) / static::$resultsPerPage );
		$pagination = Paginator::make( 1, count( $list ), static::$resultsPerPage );
		$pageInfo = array( 'actual' => $page, 'total' => $total );
		return array( 'users' => $users, 'pagination' => $pagination, 'pageInfo' => $pageInfo );
	}
}

class InstafriendsUtil{
	public static function processUsersArray( $arrayToProcess ){
		$t = array();
		foreach ($arrayToProcess as $data) {
			$t[$data['username']]['username'] = $data['username'];
			$t[$data['username']]['profile_picture'] = $data['profile_picture'];
			$t[$data['username']]['full_name'] = $data['full_name'];
			$t[$data['username']]['id'] = $data['id'];
		}
		return $t;
	}
	public static function getLanguage(){
		return 'en';
	}
}