<% control Messages %>
	<div class="project_message">
		<div>$Author.Name</div>
		<div>$Created.Nice</div>
		$MessageText
		<% if Attachments %>
			<ul>
			<% control Attachments %>
				<li><img src="$Icon" alt="" /> <a href="$URL"> [$Name <% _t('SSP.ATTACHED','attached') %>]</a></li>
			<% end_control %>
			</ul>	
		<% end_if %>
		<% if PaymentOption %>
			<div>
				<% control PaymentOption %>
					<% _t('SSPAdmin.PAYMENTOPTION','Payment option') %>: $Description ($Cost.Nice)
					<% _t('SSPAdmin.PAID','Paid?') %> $Paid.Nice
				<% end_control %>
			</div>
		<% end_if %>
	</div>
<% end_control %>
