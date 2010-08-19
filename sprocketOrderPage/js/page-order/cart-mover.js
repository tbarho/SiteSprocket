jQuery(document).ready(function() {
	
	var floater = $('#price-info');
	var floaterTop = floater.offset().top;
	var floaterLeft = floater.offset().left;
	var floaterHeight = floater.height();
	
	var content = $('.form-content');
	var contentTop = content.offset().top;
	var contentHeight = content.height();
	
	var bottomHeight = contentHeight - floaterHeight - 71;  // 43px of padding
	
	// if the scroll top is greater than 
	// the height to the bottom of the form = form.top + form.height (minus the height of the floater)
	// then set the offset to that total height minus the height of the scroller
	

	
	var cssFloating = {
		'position' : 'fixed',
		'top' : '26px',
		'left' : floaterLeft + 'px'
	}
	
	var cssFixed = {
		'position' : 'relative',
		'top' : 0,
		'left' : 0
	}
	
	var cssFixedBottom = {
		'position' : 'relative',
		'top' : bottomHeight + 'px',
		'left' : 0
	}
	
	$(window).scroll(function() {
		
		//console.log($(this).scrollTop() + " | " + (contentTop + contentHeight - floaterHeight));
		
		if($(this).scrollTop() > (floaterTop - 26)) {
			if($(this).scrollTop() > (contentTop + contentHeight - floaterHeight)) {
				floater.css(cssFixedBottom);
			} else {
				floater.css(cssFloating);
			}
		} else {
			floater.css(cssFixed);
		}
		
	});
});