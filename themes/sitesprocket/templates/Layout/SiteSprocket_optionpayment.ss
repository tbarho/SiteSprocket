
			<% require themedCSS(page-payment) %>
			<% require javascript(sprocketOrderPage/js/page-payment/cc-buttons.js) %>
			
			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1><% sprintf(_t('SSP.OPTIONPAYMENT', 'Pay for option "%s"'),$OptionName) %></h1>
					</div>
				</div>
			</div>
			<div id="main">
				<div class="checkout">
					<h2>Payment Details</h2>
					<p>Enter your payment details and click "Place Order" to place your order.</p>
					$PaymentForm
				</div>
			</div>
