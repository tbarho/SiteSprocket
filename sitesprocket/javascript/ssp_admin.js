(function($) {
$(function() {
	link = $('#sspadmin_right').metadata().link;
	// Will load the link the right panel via ajax.
	$('a[rel=right], .autocomplete_results ul li a').live("click", function() {
		$t = $(this);
		$('#sspadmin_right').load($t.attr('href'));
		return false;
	});
	
	// Update behaviours for the results table
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
	
	
	// Detail page, update CSR, status
	$('#CSRID, #Status').live("change", function() {
		$.post(
			link+'updateproject',
			{
				val : $(this).val(),
				name : $(this).attr('name'),
				id : $('#project').metadata().id
			},
			function(data) {
				$('#update_message').text(data);
			}
		);
	});
	
	// ajaxform can't handle file inputs!
	$('input[type=file]').livequery(function() {
		$(this).each(function() {
			$input = $(this);
			name = $input.attr('name');
			id = $input.attr('id');
			klass = $input.attr('class');
			$input.replaceWith($('<input type="text" class="'+klass+'" name="'+name+'" id="'+id+'" />'));
		});
	});
	
	// Add a new message
	$('#Form_CreateMessageForm').live("submit", function() {
		$(this).ajaxSubmit({
			target : '#messages',
			success : function() {
				$('textarea, :text, input[name=Attachments]').val('');
			}
		});
		return false;
	});
	

});
})(jQuery);