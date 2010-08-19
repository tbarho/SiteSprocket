	$('#dialog').dialog({
		autoOpen: false,
		height: 350,
		width: 400,
		modal: true,
		title: "Sign In"
	});
	
jQuery(document).ready(function() {

	$('#modal').click(function(){
		$('#dialog').dialog('open');
		return false;
	});
});