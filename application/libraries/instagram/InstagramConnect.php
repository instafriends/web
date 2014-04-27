<?php
/**
* Instagram PHP implementation API
* Based on Instagram-PHP-API: https://github.com/macuenca/Instagram-PHP-API/
*/
require_once 'CurlHttpClient.php';

class InstagramConnect {
	const RESPONSE_CODE_PARAM = 'code';
	protected $_endpointUrls = array(
	'authorize' => 'https://api.instagram.com/oauth/authorize/?scope=basic+comments+relationships+likes&client_id=%s&redirect_uri=%s&response_type=%s',
	'access_token' => 'https://api.instagram.com/oauth/access_token',
	'user' => 'https://api.instagram.com/v1/users/%d/?access_token=%s',
	'user_feed' => 'https://api.instagram.com/v1/users/self/feed?access_token=%s&max_id=%d&min_id=%d',
	'user_recent' => 'https://api.instagram.com/v1/users/%d/media/recent/?access_token=%s',
	'user_search' => 'https://api.instagram.com/v1/users/search?q=%s&access_token=%s',
	'user_follows' => 'https://api.instagram.com/v1/users/%d/follows?count=1000&access_token=%s',
	'user_followed_by' => 'https://api.instagram.com/v1/users/%d/followed-by?count=1000&access_token=%s',
	'user_requested_by' => 'https://api.instagram.com/v1/users/self/requested-by?access_token=%s',
	'user_relationship' => 'https://api.instagram.com/v1/users/%d/relationship?access_token=%s',
	'modify_user_relationship' => 'https://api.instagram.com/v1/users/%d/relationship',
	'media' => 'https://api.instagram.com/v1/media/%d?access_token=%s',
	'media_search' => 'https://api.instagram.com/v1/media/search?lat=%s&lng=%s&max_timestamp=%d&min_timestamp=%d&distance=%d&access_token=%s',
	'media_popular' => 'https://api.instagram.com/v1/media/popular?access_token=%s',
	'media_comments' => 'https://api.instagram.com/v1/media/%d/comments?access_token=%s',
	'post_media_comment' => 'https://api.instagram.com/v1/media/%d/comments?access_token=%s',
	'delete_media_comment' => 'https://api.instagram.com/v1/media/%d/comments?comment_id=%d&access_token=%s',
	'likes' => 'https://api.instagram.com/v1/media/%d/likes?access_token=%s',
	'post_like' => 'https://api.instagram.com/v1/media/%d/likes',
	'remove_like' => 'https://api.instagram.com/v1/media/%d/likes?access_token=%s',
	'tags' => 'https://api.instagram.com/v1/tags/%s?access_token=%s',
	'tags_recent' => 'https://api.instagram.com/v1/tags/%s/media/recent?max_id=%d&min_id=%d&access_token=%s',
	'tags_search' => 'https://api.instagram.com/v1/tags/search?q=%s&access_token=%s',
	'locations' => 'https://api.instagram.com/v1/locations/%d?access_token=%s',
	'locations_recent' => 'https://api.instagram.com/v1/locations/%d/media/recent/?max_id=%d&min_id=%d&max_timestamp=%d&min_timestamp=%d&access_token=%s',
	'locations_search' => 'https://api.instagram.com/v1/locations/search?lat=%s&lng=%s&foursquare_id=%d&distance=%d&access_token=%s',
	);
	
	protected $_config = array();
	protected $_arrayResponses = false;
	public $_oauthToken = null;
	public $_accessToken = null;
	protected $_currentUser = null;
	protected $_httpClient = null;
	public function __construct($config = null, $arrayResponses = false) {
		$this->_config = $config;
		$this->_arrayResponses = $arrayResponses;
		if (empty($config)) {
			throw new InstagramException('Configuration params are empty or not an array.');
		}
	}
	protected function _initHttpClient($uri, $method = CurlHttpClient::GET) {
		if ($this->_httpClient == null) {
			$this->_httpClient = new CurlHttpClient($uri);
		} else {
			$this->_httpClient->setUri($uri);
		}
		$this->_httpClient->setMethod($method);
	}
	protected function _getHttpClientResponse() {
		return $this->_httpClient->getResponse();
    }
	protected function _setOauthToken() {
		if( Session::has( '_oauthToken' ) ){
			$this->_oauthToken = Session::get( '_oauthToken' ); 
			return true;
		}
		$this->_initHttpClient($this->_endpointUrls['access_token'], CurlHttpClient::POST);
		$this->_httpClient->setPostParam('client_id', $this->_config['client_id']);
		$this->_httpClient->setPostParam('client_secret', $this->_config['client_secret']);
		$this->_httpClient->setPostParam('grant_type', $this->_config['grant_type']);
		$this->_httpClient->setPostParam('redirect_uri', $this->_config['redirect_uri']);
		$this->_httpClient->setPostParam('code', $this->getAccessCode());
		$this->_oauthToken = json_decode($this->_getHttpClientResponse());
		if( isset( $this->_oauthToken->code ) && $this->_oauthToken->code == 400 ){
			return false;
		} else {
			Session::put( '_oauthToken', $this->_oauthToken );
			return true;
		}
    }
	public function getAccessToken() {
		if ($this->_accessToken == null) {
			if ($this->_oauthToken == null) {
				$this->_setOauthToken();
			}
			if( isset( $this->_oauthToken->code ) && $this->_oauthToken->code == 400 ){
				return false;
			}
			$this->_accessToken = $this->_oauthToken->access_token;
		}
		return $this->_accessToken;
	}
	public function getCurrentUser() {
		if ($this->_currentUser == null) {
			if ($this->_oauthToken == null) {
				$this->_setOauthToken();
			}
			if( isset( $this->_oauthToken->code ) && $this->_oauthToken->code == 400 ){
				return false;
			}
			$this->_currentUser = $this->_oauthToken->user;
		}
		return $this->_currentUser;
	}
    protected function getAccessCode() {
		if( Session::has( self::RESPONSE_CODE_PARAM ) ) {
        	return Session::get( self::RESPONSE_CODE_PARAM );
		} else {
			return false;
		}
    }
	public function setAccessToken($accessToken) {
        $this->_accessToken = $accessToken;
    }
    public function openAuthorizationUrl() {
		$authorizationUrl = sprintf($this->_endpointUrls['authorize'],
			$this->_config['client_id'],
			$this->_config['redirect_uri'],
			self::RESPONSE_CODE_PARAM);
		return $authorizationUrl;
	}
	public function setRedirectURI( $uri ){
		$this->_config['redirect_uri'] = $uri;
	}
	public function getUser($id) {
		$endpointUrl = sprintf($this->_endpointUrls['user'], $id, $this->getAccessToken());
		$this->_initHttpClient($endpointUrl);
		return $this->_getHttpClientResponse();
	}
	public function getUserFeed($maxId = null, $minId = null) {
		$endpointUrl = sprintf($this->_endpointUrls['user_feed'], $this->getAccessToken(), $maxId, $minId);
		$this->_initHttpClient($endpointUrl);
		return $this->_getHttpClientResponse();
    }
	public function getUserRecent($id) {
		$endpointUrl = sprintf($this->_endpointUrls['user_recent'], $id, $this->getAccessToken());       
		$this->_initHttpClient($endpointUrl);
		return $this->_getHttpClientResponse();
	}
    public function getByCursor($url) {
		$endpointUrl = $url;
		$this->_initHttpClient($endpointUrl);
		return $this->_getHttpClientResponse();
    }
	public function searchUser($name) {
		$endpointUrl = sprintf($this->_endpointUrls['user_search'], $name, $this->getAccessToken());
		$this->_initHttpClient($endpointUrl);
		return $this->_getHttpClientResponse();
	}
	public function getUserFollows($id) {
        $endpointUrl = sprintf($this->_endpointUrls['user_follows'], $id, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function getUserFollowedBy($id) {
        $endpointUrl = sprintf($this->_endpointUrls['user_followed_by'], $id, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function getUserRequestedBy() {
        $endpointUrl = sprintf($this->_endpointUrls['user_requested_by'], $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function getUserRelationship($id) {
        $endpointUrl = sprintf($this->_endpointUrls['user_relationship'], $id, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function modifyUserRelationship($id, $action) {	
        $endpointUrl = sprintf($this->_endpointUrls['modify_user_relationship'], $id);
		$this->_initHttpClient($endpointUrl, CurlHttpClient::POST);
		$this->_httpClient->setPostParam('access_token', $this->getAccessToken());
		$this->_httpClient->setPostParam('action', $action);
        return $this->_getHttpClientResponse();
    }
	public function getMedia($id) {
        $endpointUrl = sprintf($this->_endpointUrls['media'], $id, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function mediaSearch($lat, $lng, $maxTimestamp = '', $minTimestamp = '', $distance = '') {
        $endpointUrl = sprintf($this->_endpointUrls['media_search'], $lat, $lng, $maxTimestamp, $minTimestamp, $distance, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function getPopularMedia() {
        $endpointUrl = sprintf($this->_endpointUrls['media_popular'], $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function getMediaComments($id) {
        $endpointUrl = sprintf($this->_endpointUrls['media_comments'], $id, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function postMediaComment($id, $text) {
        $this->_init();
        $endpointUrl = sprintf($this->_endpointUrls['post_media_comment'], $id, $text, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl, CurlHttpClient::POST);
        return $this->_getHttpClientResponse();
    }
	public function deleteComment($mediaId, $commentId) {
        $endpointUrl = sprintf($this->_endpointUrls['delete_media_comment'], $mediaId, $commentId, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl, CurlHttpClient::DELETE);
        return $this->_getHttpClientResponse();
    }
	public function getLikes($mediaId) {
        $endpointUrl = sprintf($this->_endpointUrls['likes'], $mediaId, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function postLike($mediaId) {
        $endpointUrl = sprintf($this->_endpointUrls['post_like'], $mediaId);
        $this->_initHttpClient($endpointUrl, CurlHttpClient::POST);
        $this->_httpClient->setPostParam('access_token', $this->getAccessToken());
        return $this->_getHttpClientResponse();
    }
	public function removeLike($mediaId) {
        $endpointUrl = sprintf($this->_endpointUrls['remove_like'], $mediaId, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl, CurlHttpClient::DELETE);
        return $this->_getHttpClientResponse();
    }
	public function getTags($tagName) {
        $endpointUrl = sprintf($this->_endpointUrls['tags'], $tagName, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function getRecentTags($tagName, $maxId = '', $minId = '') {
        $endpointUrl = sprintf($this->_endpointUrls['tags_recent'], $tagName, $maxId, $minId, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function searchTags($tagName) {
        $endpointUrl = sprintf($this->_endpointUrls['tags_search'], urlencode($tagName), $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function getLocation($id) {
        $endpointUrl = sprintf($this->_endpointUrls['locations'], $id, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function getLocationRecentMedia($id, $maxId = '', $minId = '', $maxTimestamp = '', $minTimestamp = '') {
        $endpointUrl = sprintf($this->_endpointUrls['locations_recent'], $id, $maxId, $minId, $maxTimestamp, $minTimestamp, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
	public function searchLocation($lat, $lng, $foursquareId = '', $distance = '') {
        $endpointUrl = sprintf($this->_endpointUrls['locations_search'], $lat, $lng, $foursquareId, $distance, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
}
class InstagramException extends Exception {}

