
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
				<div class="sidebar">
					<div class="cart">
						<h2>Order Summary</h2>
						<div id="price-info">
							<table cellspacing="0" cellpadding="0">
								<thead>
									<tr>
										<th><% _t('SSP.PRODUCTHEADER','Product') %></th>
										<th><% _t('SSP.PRICEHEADER','Price') %></th>
									</tr>
								</thead>
								<tbody id="price-update">
									<tr class="line-item">
										<td>$OptionName</td>
										<td>$OptionCost</td>
									</tr>
									<tr class="total">
										<td><strong>TOTAL</strong></td>
										<td class="price"><strong>$OptionCost</strong></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
