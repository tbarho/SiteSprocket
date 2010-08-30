<h3><% _t('SSP.ORDERDETAILS','Order details') %></h3>
<% control SelectedOptions %>
	<div>
		<% control Option %>
			$Title ($Price.Nice)<br />
		<% end_control %>
		<% if Attachments %>
			<% control Attachments %>
				<img src="$Icon" alt="" /> <a href="$URL">[$Name <% _t('SSP.ATTACHED','attached') %>]</a><br />
			<% end_control %>
		<% end_if %>
	</div>
<% end_control %>
<% if Notes %>
	<h3><% _t('SSP.NOTES','Notes') %></h3>
	<div>$Notes</div>
<% end_if %>
<div>Total paid by credit card: $TotalCost.Nice</div>
