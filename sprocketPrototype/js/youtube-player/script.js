$(document).ready(function(){
	
	var vidID = "ZJqZ_yp4REs";
	
	var ytThumb = "http://img.youtube.com/vi/"+vidID+"/1.jpg";
	
	$('#thumb').append('<a href="#" class="video-trigger"><img src="'+ytThumb+'" alt="Watch Video" /></a>');
	
	$('#player').youTubeEmbed("http://www.youtube.com/watch?v="+vidID);
				
	
	$('.video-trigger').each(function(index) {
		$(this).click(function() {
			$('#video-container').slideDown();
			$('.video').fadeOut();
			return false;
		});
		
	});
	
	
	$('#close-video').click(function (){
		$('.video').fadeIn();
		$('#video-container').slideUp();
		
		return false;
	});

	$('form').submit(function(){
		$('#player').youTubeEmbed($('#url').val());
		$('#url').val('');
		return false;
	});

});
