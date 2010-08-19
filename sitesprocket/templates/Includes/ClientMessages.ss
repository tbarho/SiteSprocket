<% control Messages %>
	<div class="project_message">
		<h3><% if You %><% _t('SSP.YOU','You') %><% else %>$Author.Name<% end_if %></h3>
		<h4>$Created.Format(m-d-Y)</h4>
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
					<% if Paid %>
						<% _t('SSP.PAID','PAID') %>
					<% else %>
						<a href="$PayLink"><% _t('SSP.PAYFOROPTION','Pay') %></a>
					<% end_if %>
				<% end_control %>
			</div>
		<% end_if %>
		
	</div>
<% end_control %>
