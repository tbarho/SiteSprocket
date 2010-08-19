jQuery(document).ready(function() {	
	
	$('#Form_OrderForm input:checkbox').each(function() {
		if($(this).is(':checked')) {
			$(this).parents('.product').addClass('selected');
		} else {
			$(this).parents('.product').removeClass('selected');
		}
		
		$(this).change(function () {
			$(this).parents('.product').toggleClass('selected');
		});
	});
});