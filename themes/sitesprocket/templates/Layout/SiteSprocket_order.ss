			<% require themedCSS(page-order) %>
			<% require javascript(sprocketOrderPage/js/page-order/cart-mover.js) %>
			<% require javascript(sprocketOrderPage/js/page-order/check-change.js) %>

			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1>Order Now!</h1>
					</div>
				</div>
			</div>
			<div id="main">
				
				<div class="form-content">
					<h2>Select your options</h2>
					<% control OrderForm %>
					<form $FormAttributes>
						<% control Fields %>
						$FieldHolder
						<% end_control %>
					</form>
					<% end_control %>
				</div>
				<div class="cart">
					<h2>Order Summary</h2>
					<div id="price-info">
						<table cellspacing="0" cellpadding="0">
							<thead>
								<tr>
									<th><% _t('SSP.PRODUCTHEADER','Product') %></th>
									<th class="price"><% _t('SSP.PRICEHEADER','Price') %></th>
								</tr>
							</thead>
							<tbody id="price-update">
								<% include PriceTable %>
							</tbody>
						</table>
						<div class="order-section">
							<input id="order-btn" onclick="$('#Form_OrderForm').submit();" class="order-btn" type="image" value="Submit" src="themes/sitesprocket/images/order-now-large.png" alt="Order Now!" />
						</div>
						<div class="terms">
							<strong>Terms of Service</strong>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse 
							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt 
							mollit anim id est laborum.</p>
						</div>
						<div class="payments">
							<strong>Payments Accepted</strong>
							<ul>
								<li><img src="themes/sitesprocket/images/img-cards-1.gif" alt="Cards" /></li>
							</ul>
							
						</div>
					</div>
				</div>
			</div>