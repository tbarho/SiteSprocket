			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1>
						Thank you for your order!
						</h1>
					</div>
				</div>
			</div>
			<div id="main">
				<h2>Here's what you submitted</h2>
				<% control Project %>
					<h3><% _t('SSP.PERSONALINFO','Personal information') %></h3>
					<% control Creator %>
						<div>Name: $FirstName $Surname</div>
						<div>Email: $Email</div>
					<% end_control %>
					
					<h3><% _t('SSP.ORDERINFO','Order info') %></h3>
					<% control SelectedOptions %>
						<div>
							<% control Option %>
								$Title ($Price.Nice)<br />
							<% end_control %>
							<% if Attachments %>
								<% control Attachments %>
									<img src="$Icon" alt="" /> <a href="$URL">[$Name attached]</a><br />
								<% end_control %>
							<% else %>
								no attachments
							<% end_if %>
						</div>
					<% end_control %>
					
					<h3><% _t('SSP.PAYMENTINFO','Payment info') %></h3>
					<div>Total paid by credit card: $TotalCost.Nice</div>
				<% end_control %>
				
				<h3><a href="$Link(projects)"><% _t('SSP.GOTOPROJECTS','Go to your projects') %></a></h3>
			</div>
