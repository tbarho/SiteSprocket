<div class="order">
	<h3><% _t('SSP.ORDERDETAILS','Initial Order details') %></h3>
	<% control SelectedOptions %>
	<div class="options">
		<div class="option">
			<% control Option %>
			<div class="title">$Title <span>$Price.Nice</span></div>
			<div class="description">$Description</div>
			<% end_control %>
			<% if Attachments %>
			<ul class="uploads">
				<% control Attachments %>
				<li>$Name <% _t('SSP.ATTACHED','attached') %> <a href="$URL">download</a></li>
				<% end_control %>
			</ul>
			<% end_if %>
		</div>
	</div>
	<% end_control %>
	<div class="payment-info">Total paid by credit card: $TotalCost.Nice</div>
	<% if Notes %>
	<div class="notes">
		<h3><% _t('SSP.NOTES','Notes') %></h3>
		<p>$Notes</p>
	</div>
	<% end_if %>
</div>

