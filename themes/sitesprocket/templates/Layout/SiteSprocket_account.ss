			<% require themedCSS(page-create-account) %>
			<% require css(sprocketOrderPage/css/ui-lightness/jquery-ui-1.8.4.custom.css) %>
			<% require javascript(http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/jquery-ui.min.js) %>
			<% require javascript(sprocketOrderPage/js/page-create-account/dialogue.js) %>
			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1>Sign In</h1>
					</div>
				</div>
			</div>
			<div id="main">
				<div class="create-account">
					<div class="head">
						<h2><% _t('SSP.NEWACCOUNT','Create an account') %></h2>
						<a href="#" id="modal">Already have one?</a>
					</div>
					<div class="form-content">
						$AccountForm

					</div>
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
									<% include PriceTable %>
								</tbody>
							</table>
						</div>
					</div>
					
					<h4>With an account you can:</h4>
					<ul>
						<li>Easily purchase new sites</li>
						<li>Manage your website projects online</li>
						<li>Store your profile information</li>
						<li>Get better tech support</li>
						<li>Be awesome!</li>
					</ul>
				</div>
				
				<div id="dialog">
					$LoginForm
				</div>

			</div>
