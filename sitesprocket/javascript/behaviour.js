(function($) {
$(function() {
	// ajaxform can't handle file inputs!
	$('input[type=file]').each(function() {
		$input = $(this);
		name = $input.attr('name');
		id = $input.attr('id');
		klass = $input.attr('class');
		$input.replaceWith($('<input type="text" class="'+klass+'" name="'+name+'" id="'+id+'" />'));
	});

	// Set up ajax form submit for the package selection
	if($('#Form_OrderForm').length) {
		$.getScript('/sitesprocket/javascript/jquery.form.js', function() {
			$('#Form_OrderForm input[name^=Option_]').unbind("click").click(function() {
				$('#Form_OrderForm').ajaxSubmit({
					target : '#price-update'
				});
			});
		});
	}
	
	// Assign behaviours to the results table
	if($('.project_results').length) {
		$('.project_results thead a, .project_results tfoot a').live("click", function() {
			$t = $(this);
			$('.project_results').load($t.attr('href'));
			return false;
		});
		$('.project_results tfoot select').live("change",function() {
			$t = $(this);
			$('.project_results').load(
				$t.metadata().url,
				{'PerPage' : $t.val()}
			);
		});
	}
		
	// Add a message
	if($('#Form_CreateMessageForm').length) {	
		$.getScript('/sitesprocket/javascript/jquery.form.js', function() {
			$('#Form_CreateMessageForm').live("submit", function() {
				$(this).ajaxSubmit({
					target : '#messages',
					success : function() {
						$('textarea').val('');
						$('.uploadify-filename').remove();
						$('input[name^=Attachments]').remove();
					}
				});
				return false;
			});
		});
	}
	
	// Payment form
	if($('.custom_address').length) {
		$check = $('input[id$=CustomAddress]');
		$address = $('.custom_address');
		if(!$check.is(':checked')) {
			$address.hide();
		}
		$check.click(function() {
			if($(this).is(':checked')) {
				$address.slideDown();
			}
			else {
				$address.slideUp();
			}
		});
	}
	
	// Lightbox
	if($("div.more-box").length) {	
		$.getScript("/sitesprocket/javascript/fancybox.js", function() {
			$("div.more-box").hide();
			$("a[rel=fb]").fancybox({hideOnContentClick:false, frameWidth:600, frameHeight:400});			
		});
	}
	
	
	
});
})(jQuery);