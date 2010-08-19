(function($) {
$(function() {
		$('.uploadify').livequery(function() {
			$(this).each(function() {
				opts = $(this).metadata();
				$.extend(opts, {
					onComplete: function(event, queueID, fileObj, response, data) {
						if(isNaN(response)) {
							alert(response);
							return;
						}
						$e = $(event.currentTarget);
						multi = $e.uploadifySettings('multi');
						name = $e.attr('name');
						name += (multi) ? "[]" : "ID";
						if(!multi) {
							$('input[name='+name+']').remove();
							$('.uploadify-filename',$e.parent()).remove();
						}
						$e.parent().append($('<div class="uploadify-filename">'+fileObj.name+'</div>'));
						$e.parent().append($('<input type="hidden" name="'+name+'" value="'+response+'" />'));
					}
				});
				$(this).uploadify(opts);
			});
		});
});
})(jQuery);