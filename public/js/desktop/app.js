var modalOriginalHTML = '';
function fastLoadUserInfo( id ){
	$('#modal-from-dom').modal({
			'show': true, 
			'backdrop' : 'static', 
			'keyboard' : true });
	loadUserInfo( id );	
}
function loadUserInfo( id ){
	$('#modal-from-dom').html(modalOriginalHTML);
	var url = userInfoUrl + id;
	$.ajax({
	  url: url,
	  success: function( content ){
	    $('#modal-from-dom').html(content);
	  }
	});
}
function userPhotos( id ){
	$('#userPhotos').html(loading);
	var url = userPhotosUrl + id;
	$.ajax({
	  url: url,
	  success: function( content ){
	    $('#userPhotos').html(content);
	  }
	});	
}
function fastFollowUser(id){
	$('#action-' + id).html('<span class="wait"></span>');
	var url = userFollowUrl + id;
	$.ajax({
	  url: url,
	  success: function( script ){
	   $('#action-' + id).html('<span class="unfollow" id="unfollow-' + id + '" onclick="fastUnfollowUser(' + id + ');">UNFOLLOW</span>');
	  }
	});
}
function followUser( id ){
	$('#relationshipButton').html(loading);
	var url = userFollowUrl + id;
	$.ajax({
	  url: url,
	  success: function( script ){
	   $('head').append(script);
	  }
	});
}
function followMe( id ){
	$('#followMeButton').html(loading);
	var url = userFollowUrl + id;
	$.ajax({
	  url: url,
	  success: function( script ){
		$('#followMeButton').html('Thanks! :)');
	}
	});
}
function fastUnfollowUser( id ){
	$('#action-' + id).html('<span class="wait"></span>');
	var url = userUnfollowUrl + id;
	$.ajax({
	  url: url,
	  success: function(){
	    $('#action-' + id).html('<span class="follow" id="follow-' + id + '" onclick="fastFollowUser(' + id + ');">FOLLOW</span>');
	  }
	});
}
function unfollowUser( id ){
	$('#relationshipButton').html(loading);
	var url = userUnfollowUrl + id;
	$.ajax({
	  url: url,
	  success: function( script ){
	   $('head').append(script);
	   $('#' + id).hide();
	  }
	});
}
function userTemplate( user, followedBy ){
	var template = new Array();
	 template.push('<li id="user-' + user.id + '">');
		template.push('<a class="user" title="' + user.username + '">');
			template.push('<div id="options-' + user.id + '" class="options">');
				template.push('<span id="action-' + user.id + '">');
				if(followedBy){
					template.push('<span class="unfollow" id="unfollow-' + user.id + '" onclick="fastUnfollowUser(' + user.id + ');">UNFOLLOW</span>');
				} else { 
					template.push('<span class="follow" id="follow-' + user.id + '" onclick="fastFollowUser(' + user.id + ');">FOLLOW</span>');
				}
				template.push('</span>');
				template.push('<span class="info" onclick="fastLoadUserInfo(' + user.id + ');">INFO</span>');
			template.push('</div>');
			template.push('<img width="80" height="80" class="thumbnail" src="' + user.profile_picture + '">');
		template.push('</a>');
	template.push('</li>');
	return template.join('')
}
var stepsToShow = 30;
function showfriends( stepsToShow ){
	for( i = friendsShowing; i < ( friendsShowing + stepsToShow ); i++ ){
		if( i >= friends.length ){ break; }
		var user = friends[i];
		$('#users-friends').append( userTemplate( user, true ) );
	}
	friendsShowing = friendsShowing + stepsToShow;
	if( friendsShowing >= friends.length ){
		$('#btn-friends').fadeOut('fast');
	}
}
function showfans( stepsToShow ){
	for( i = fansShowing; i < ( fansShowing + stepsToShow ); i++ ){
		if( i >= fans.length ){ break; }
		var user = fans[i];
		$('#users-fans').append( userTemplate( user, false ) );
	}
	fansShowing = fansShowing + stepsToShow;
	if( fansShowing >= fans.length ){
		$('#btn-fans').fadeOut('fast');
	}
}
function showfollowing( stepsToShow ){
	for( i = followingShowing; i < ( followingShowing + stepsToShow ); i++ ){
		if( i >= following.length ){ break; }
		var user = following[i];
		$('#users-following').append( userTemplate( user, true ) );
	}
	followingShowing = followingShowing + stepsToShow;
	if( followingShowing >= following.length ){
		$('#btn-following').fadeOut('fast');
	}
}
function showAbout(){
	$('#modal-from-dom').modal({'show': true, 'backdrop' : 'static', 'keyboard' : true });
}
function showLog(){
	$('#modal-from-dom-log').modal({'show': true, 'backdrop' : 'static', 'keyboard' : true });
}