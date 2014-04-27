<?php

class Desktop_Controller extends Base_Controller {

	public function __construct(){
		$this->if = new Instafriends();
	}

	public function action_index(){
		return View::make( 'desktop.index' )->with( 'authorizeUrl', URL::to('/desktop/authorize/'));
	}
	
	public function action_authorize(){
		return $this->if->getAuthorizationURL();
	}

	public function action_authorized(){
		if( $this->if->authorizeUser() ){
			return Redirect::to( 'desktop/dashboard' );
		} else {
			die('auth fail');
		}
	}

	public function action_data(){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		$this->if->dashboard();
		//Friends
		$content = View::make('desktop/data')
					->with('friends', $this->if->getFriends())
					->with('section', 'friends')
					->with('title', 'These %d people are following you, and you are following them back.');
		//Fans
		$content .= View::make('desktop/data')
					->with('friends', $this->if->getStalkers())
					->with('section', 'fans')
					->with('title', 'These %d people are following you, but you are not following them back.');
		//Following
		$content .= View::make('desktop/data')
					->with('friends', $this->if->getFans())
					->with('section', 'following')
					->with('title', 'You are following these %d people, but they are not following you back.');
		$content .= '<script>
						$("#data-fans").html(' . count( $this->if->getStalkers() ) . ');
						$("#data-friends").html(' . count( $this->if->getFriends() ) . ');
						$("#data-following").html(' . count($this->if->getFans()) . ');
					</script>';
		echo $content;
	}

	public function action_logout(){
		$this->if->logout();
		return Redirect::to( 'desktop/' );
	}

	public function action_follow( $id ){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		$this->if->followUser( $id );
		return '<script>loadUserInfo(' . $id . ')</script>';;
	}

	public function action_about( ){
		$content = View::make( 'mobile.about' )
						->with( 'bodyTitle', Lang::line( 'instafriends.about' )->get( $this->lang() ) );
		return $this->show( $content );
	}

	public function action_unfollow( $id ){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		$this->if->unfollowUser( $id );
		return '<script>loadUserInfo(' . $id . ')</script>';
	}

	public function action_followme(){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		$this->if->followUser( Config::get( 'instagram.pererinhaID' ) );
		return Redirect::to( 'mobile/dashboard' );
	}

	public function action_dashboard(){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		return View::make('desktop/dashboard')->with('user', $this->if->getCurrentUserInfo());
	}

	public function action_userinfo( $id ){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		$userInfo = $this->if->getUserInfo($id);
		return View::make('desktop/userinfo')->with('user', $userInfo);
	}

	public function action_photos( $id ){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		$photos = $this->if->getMediaRecentByUser( $id );
		return View::make('desktop/photos')->with('photos', $photos);
	}

	private function lang(){
		return InstafriendsUtil::getLanguage();
	}

	private function show( $content, $isDashBoard = false ){
		Asset::add( 'jquery', 'js/jquery-1.7.2.min.js' );
		$layout  = View::make( 'mobile/base' )
					->with( 'pageTitle', Lang::line( 'instafriends.instafriends' )->get( $this->lang() ) )
					->with( 'header', $this->header( $isDashBoard ) )
					->with( 'footer', '' )
					->with( 'content', $content);
		return $layout;
	}

	private function header( $isDashBoard ){
		if( $this->if->isLogged() ){
			$user = $this->if->getCurrentUserInfo();
			$logoutTitle = Lang::line( 'instafriends.logout' )->get( $this->lang() ); 
			return View::make( 'mobile/header-logged' )
												->with( 'user', $user )
												->with( 'logoutTitle', $logoutTitle )
												->with( 'isDashBoard', $isDashBoard );	
		} else {
			return View::make( 'mobile/header-not-logged' )
												->with( 'isDashBoard', $isDashBoard );		
		}
		
	}

	private function needToLogin(){
		return Redirect::to( 'mobile/' );
	}
}