<?php

class Mobile_Controller extends Base_Controller {

	public function __construct(){
		$this->if = new Instafriends();
	}

	public function action_index(){
		$content = View::make( 'mobile.index' )
						->with( 'bodyTitle', Lang::line( 'instafriends.instafriends' )->get( $this->lang() ) )
						->with( 'aboutTitle', Lang::line( 'instafriends.about' )->get( $this->lang() ) )
						->with( 'bodyTitle', Lang::line( 'instafriends.instafriends' )->get( $this->lang() ) )
						->with( 'description', Lang::line( 'instafriends.description' )->get( $this->lang() ) );
		return $this->show( $content, true );
	}
	
	public function action_authorize(){
		return $this->if->getAuthorizationURL();
	}

	public function action_authorized(){
		if( $this->if->authorizeUser() ){
			return Redirect::to( 'mobile/dashboard' );
		} else {
			die('auth fail');
		}
	}

	public function action_logout(){
		$this->if->logout();
		return Redirect::to( 'mobile/' );
	}

	public function action_follow( $id ){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		$this->if->followUser( $id );
		return View::make( 'mobile.buttonAction' )
					->with( 'actionTitle', Lang::line( 'instafriends.unfollow' )->get( $this->lang() ) )
					->with( 'id', $id )
					->with( 'actionJS', 'unfollow' );
	}

	public function action_about( ){
		$content = View::make( 'mobile.about' )
						->with( 'bodyTitle', Lang::line( 'instafriends.about' )->get( $this->lang() ) );
		return $this->show( $content );
	}

	public function action_unfollow( $id ){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		$this->if->unfollowUser( $id );
		return View::make( 'mobile.buttonAction' )
					->with( 'actionTitle', Lang::line( 'instafriends.follow' )->get( $this->lang() ) )
					->with( 'id', $id )
					->with( 'actionJS', 'follow' );
	}

	public function action_followme(){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		$this->if->followUser( Config::get( 'instagram.pererinhaID' ) );
		return Redirect::to( 'mobile/dashboard' );
	}

	public function action_dashboard(){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		$friends 		= $this->if->dashboard();
		$bodyTitle 		= Lang::line( 'instafriends.Dashboard' )->get( $this->lang() );
		$friendsTitle 	= Lang::line( 'instafriends.friends' )->get( $this->lang() );
		$fansTitle 		= Lang::line( 'instafriends.fans' )->get( $this->lang() );
		$followingTitle	= Lang::line( 'instafriends.following' )->get( $this->lang() );
		$followmeTitle	= Lang::line( 'instafriends.follow-me' )->get( $this->lang() );
		$aboutTitle		= Lang::line( 'instafriends.about' )->get( $this->lang() );
		$content = View::make( 'mobile.dashboard' )
						->with( 'bodyTitle', $bodyTitle )
						->with( 'friendsTitle', $friendsTitle )
						->with( 'fansTitle', $fansTitle )
						->with( 'followingTitle', $followingTitle )
						->with( 'aboutTitle', $aboutTitle )
						->with( 'followmeTitle', $followmeTitle )
						->with( 'numberOfFans', $friends[ 'fans' ] )
						->with( 'numberOfFriends', $friends[ 'friends' ] )
						->with( 'numberOfFollowing', $friends[ 'stalkers' ] );
		return $this->show( $content, true );
	}

	public function action_list( $key ){
		if( !$this->if->isLogged() ){ return $this->needToLogin();}
		$list = false;
		if( $key == 'fans' ){ $list = $this->if->getFans(); }
		if( $key == 'friends' ){ $list = $this->if->getFriends(); }
		if( $key == 'following' ){ $list = $this->if->getStalkers(); }
		if( !$list ){ return Redirect::to( 'mobile/dashboard' ); }

		$list = InstafriendsPagination::paginate( $list );

		$bodyTitle   = Lang::line( 'instafriends.' . $key )->get( $this->lang() );
		$pageInfo    = Lang::line( 'instafriends.page_of', array( 'actual' => $list[ 'pageInfo' ][ 'actual' ], 'total' => $list[ 'pageInfo' ][ 'total' ] ) )->get();
		$actionTitle = ( $key == 'fans' || $key == 'friends' ) ? Lang::line( 'instafriends.unfollow' )->get( $this->lang() ) : Lang::line( 'instafriends.follow' )->get( $this->lang() ); 
		$actionJS 	 = ( $key == 'fans' || $key == 'friends' ) ? 'unfollow' : 'follow'; 

		Asset::add( 'jquery', 'js/jquery-1.7.2.min.js' );
		Asset::add( 'list', 'js/mobile/list.js', 'jquery');

		$content = View::make( 'mobile.list' )
						->with( 'bodyTitle', $bodyTitle )
						->with( 'pageInfo', $pageInfo )
						->with( 'actionTitle', $actionTitle )
						->with( 'actionJS', $actionJS )
						->with( 'users', $list[ 'users' ] )
						->with( 'pagination', $list[ 'pagination' ] );
		return $this->show( $content );
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