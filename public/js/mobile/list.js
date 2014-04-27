function follow(id){
	showListRelationLoading(id);
	url = _urlBase + 'follow/' + id;
	$.ajax({
		url: url,
		success: function( content ){ $( '#relation' + id ).html( content ); }
	});	
}

function unfollow(id){
	showListRelationLoading(id);
	url = _urlBase + 'unfollow/' + id;
	$.ajax({
		url: url,
		success: function( content ){ $( '#relation' + id ).html( content ); }
	});	
}

function showListRelationLoading(id){
	$( '#relation' + id ).html( '<div class="loading"></div>' );
}