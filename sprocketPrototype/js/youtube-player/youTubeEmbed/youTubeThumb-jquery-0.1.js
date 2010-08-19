(function($) {

	$.fn.youTubeThumb = function(url,container) {
	
		if(url === null) {
			return "";
		}
		
		var vid;
		var results;
		var vidUrl;
		
		results = url.match("[\\?&]v=([^&#]*)");
		
		vid = (results === null) ? url : results[1];
		
		vidUrl = "http://img.youtube.com/vi/"+vid+"/2.jpg";
		
		console.log($(container));
		
		$(container).append($('<img src="'+vidUrl+'" />"));
	
	}


})(jQuery);