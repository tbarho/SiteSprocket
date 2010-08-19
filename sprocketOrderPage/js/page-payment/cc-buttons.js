/*
$('#ccv-dialog').dialog({
	autoOpen: false,
	height: 350,
	width: 400,
	modal: false,
	draggable: false,
	position: [800,600]
});
*/



jQuery(document).ready(function() {
	
	$('#Form_PaymentForm_CCV').focusin(function() {
		$('#ccv-dialog').fadeTo(300, 1);
	});
	
	$('#Form_PaymentForm_CCV').focusout(function() {
		$('#ccv-dialog').fadeTo(300, 0);
	});
	
	
	
	
	
	// Fade all the CC's to .3 - taken out
	/*
$('#CardType li').each(function() {
		$(this).fadeTo(400, 0.3, function() {});
	});
*/
	
	// Set the focus to the first CC Box
	$('#CardNumber_Holder input').eq(0).focus();
	
	// This fades the CC in based on the user input;  it might be crap
	$('#CardNumber_Holder input').eq(0).keyup(function() {
		var cc_num = $(this).val();

		if(!(cc_num == "")) {
						
			switch (cc_num.charAt(0)) {
		
				
				case "4" :
					$('#CardType li').not('li.visa').fadeTo(400, 0.3, function() {});
					$('#CardType li.visa').fadeTo(400, 1, function() {});
					break;
					
				case "5" :
					$('#CardType li').not('li.mc').fadeTo(400, 0.3, function() {});
					$('#CardType li.mc').fadeTo(400, 1, function() {});
					break;
				
				case "3" :
					$('#CardType li').not('li.ae').fadeTo(400, 0.3, function() {});
					$('#CardType li.ae').fadeTo(400, 1, function() {});
					break;
				
				case "6" :
					$('#CardType li').not('li.disc').fadeTo(400, 0.3, function() {});
					$('#CardType li.disc').fadeTo(400, 1, function() {});
					break;
					
			}
		} else {
			$('#CardType li').fadeTo(400, 0.3);
		}
		
	});
	

	// Move the focus to the next CC box when there are 4 numbers in it
	$('#CardNumber_Holder input').each(function() {
		$(this).keyup(function() {
			if($(this).val().length == 4 && $(this).index() < 3) {
				$(this).next().focus();
			}
		});
	});
});