jQuery(document).ready(function() {	

	// UI Code for Order Page
	
	// First we add opening functionality to headers
	stepHeaders = $('.section h3');	
	stepHeaders.each(function() {
		$(this).click(function () {
			openStep($(this).parents('.section'));
		});
	});
	
	// Then we need to know which section has focus
	// This should be based on last checked item
	// So on page load, we find the checked items
	
	allCheckboxes = $('input:checkbox');
	checkedItems = $('input:checkbox:checked:enabled');
	
	// If there are items checked
	if(checkedItems.length) {
		// First show the selected / disabled
		checkedItems.each(function() {
			selectProduct($(this).parents('.product'));
		});
		// find the last checked boxes parent (section)
		last = $('input:checkbox:checked:enabled:last').parents('.section');
		// then hide the other Steps
		hideOtherSteps(last);
	} else {
		// find the first step
		first = $('.section:first');
		// then hide the other Steps
		hideOtherSteps(first);
	}
	
	// Add click select / unselect functionality to checkboxes
	allCheckboxes.each(function () {
		$(this).change(function () {
			product = $(this).parents('.product');
			if($(this).is(':checked')) {
				selectProduct(product);
			} else {
				unselectProduct(product);
			}
		});
	});
		
	
	
	/* Support functions */
	
	

	function hideOtherSteps(openStep) {
		// If the section has selected products
		// Then mostly hide the selected products
		// Slide the other steps product divs up to hide
		openStep.siblings('.section').children('.product').each(function() {
			minimizeProduct($(this));
		});
		openStep.siblings('.section').each(function() {
			addOpenStepButton($(this));
		});
	}
	
	function openStep(step) {
		// Open the clicked step, hide the other ones
		removeOpenStepButton(step);
		step.children('.product').each(function() {
			maximizeProduct($(this));
		});
		hideOtherSteps(step);
	}
	
	function selectProduct(product) {
		// Add the select style to the product
		product.addClass('selected');
		// Add the disabled style to other products in that section if the section is limit-one
		if(product.parents('.section').hasClass('limit-one')) {
			disableProduct(product.siblings('.product'));
		}
		// Add a next button
		addNextButton(product);
		
	}
	
	function unselectProduct(product) {
		// Remove the select style from the product
		product.removeClass('selected');
		// Remove the disabled styles from other products in that section if the section is limit-one
		if(product.parents('.section').hasClass('limit-one')) {
			enableProduct(product.siblings('.product'));
		}
		// Remove next button
		removeNextButton(product);
	}
	
	function disableProduct(product) {
		product.addClass('disabled');
		product.children('.field').children('input:checkbox').attr('disabled','disabled');
	}
	
	function enableProduct(product) {
		product.removeClass('disabled');
		product.children('.field').children('input:checkbox').removeAttr('disabled');
	}
	
	function addNextButton(product) {
		if(product.parents('.section').index() < $('.section').length) {
			// if the product is selected
			if(product.hasClass('selected')) {
				// add the button
				product.append('<div class="next-button"><a href="#">Go to Next Step &raquo;</a></div>');
				// make the button move to the next section
				product.children('.next-button').children('a').click(function($event) {
					$event.preventDefault();
					openStep(product.parents('.section').next());
				});
			}
		}
		
		
		
		
	}
	function removeNextButton(product) {
		product.children('.next-button').html('');
	}
	
	function addOpenStepButton(section) {
		removeOpenStepButton(section);
		section.append('<div class="open-button"><a href="#">(+) Open this step</a></div>');
		section.children('.open-button').children('a').click(function($event) {
			$event.preventDefault();
			openStep(section);
		});
	}
	
	function removeOpenStepButton(section) {
		section.children('.open-button').html('');
	}

	
	function minimizeProduct(product) {
		if(product.hasClass('selected')) {
			product.parents('.section').addClass('inactive');
			product.children('div').slideUp(100);
			product.find('.field input').fadeOut(100);
			removeNextButton(product);
		} else {
			product.slideUp(100);
		}
	}
	
	function maximizeProduct(product) {
		if(product.hasClass('selected')) {
			product.parents('.section').removeClass('inactive');
			product.children('div').slideDown(100);
			product.find('.field input').fadeIn(100);
			addNextButton(product);
		} else {
			product.slideDown(100);
		}
	}




/* Old Codebase */
	
	/*
$('#Form_OrderForm input:checkbox').each(function() {
		if($(this).is(':checked')) {
			$(this).parents('.product').addClass('selected');
		} else {
			$(this).parents('.product').removeClass('selected');
		}
		
		$(this).change(function () {
			$(this).parents('.product').toggleClass('selected', 300);
		});
	});
	
	// Make an accordion out of something
	
	
	// Disable on selection for "limit-one" groups
	// TODO: Persist through session....done
	// TODO: Make better use of code....
	if($('#Form_OrderForm').length) {
		$('#Form_OrderForm input:checkbox').each(function() {
			if($(this).parents('.section').hasClass('limit-one')) {
				if($(this).is(':checked')) {
					$(this).parents(".product").siblings(".product").children('.field').children('input').attr('disabled', 'disabled');
					$(this).parents(".product").siblings(".product").addClass('disabled');
				} else { 
					$(this).parents(".product").siblings(".product").children('.field').children('input').removeAttr('disabled');
					$(this).parents(".product").siblings(".product").removeClass('disabled');
				}
				$(this).change(function() {
					if($(this).is(':checked')) {
						$(this).parents(".product").siblings(".product").children('.field').children('input').attr('disabled', 'disabled');
						$(this).parents(".product").siblings(".product").addClass('disabled');
					} else { 
						$(this).parents(".product").siblings(".product").children('.field').children('input').removeAttr('disabled');
						$(this).parents(".product").siblings(".product").removeClass('disabled');
					}
				});
			}
		});
	}
*/

});