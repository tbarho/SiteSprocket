<% control Messages %>
	<div class="project_message<% if You %> you<% end_if %>">
		<div class="author"><strong>$Author.Name</strong> said on $Created.Nice</div>
		<div class="body">$MessageText</div>
		<div class="attachments">
		<% if Attachments %>
			<ul>
			<% control Attachments %>
				<li><img src="$Icon" alt="" /> <a href="$URL"> [$Name <% _t('SSP.ATTACHED','attached') %>]</a></li>
			<% end_control %>
			</ul>	
		<% end_if %>
		</div>
		<div class="payment-option">
		<% if PaymentOption %>
			<div>
				<% control PaymentOption %>
					<% _t('SSPAdmin.PAYMENTOPTION','Payment option') %>: $Description ($Cost.Nice)
					<% _t('SSPAdmin.PAID','Paid?') %> $Paid.Nice
				<% end_control %>
			</div>
		<% end_if %>
		</div>
	</div>
<% end_control %>
