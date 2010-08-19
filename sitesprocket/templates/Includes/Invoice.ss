	<table width="100%">
		<thead>
			<tr>
				<th><% _t('SSP.INVOICEPRODUCT','Product') %></th>
				<th><% _t('SSP.INVOICECOST','Cost') %></th>
			</tr>
		</thead>
		<tbody>
				<tr>
					<th colspan="2"><% _t('SSP.SELECTEDOPTIONS','Selected options') %></th>
				</tr>
				<% control SelectedOptions %>
					<% control Option %>
						<tr>
							<td>$Title</td>
							<td>$Price.Nice</td>
						</tr>
					<% end_control %>
				<% end_control %>
				<tr>
					<th colspan="2"><% _t('SSP.EXTRAOPTIONS','Extra options') %></th>
				</tr>
				<% control AcceptedPaymentOptions %>
					<tr>
						<td>$Description</td>
						<td>$Cost.Nice</td>
					</tr>
				<% end_control %>
		</tbody>
		<tfoot>
			<tr>
				<td><strong><% _t('SSP.TOTALCOSTINVOICE','Total cost') %></strong></td>
				<td>$FinalCost.Nice</td>
			</tr>
		</tfoot>
	</table>
	<p><% _t('SSP.INVOICEFOOTER','Thanks for your business!') %></p>
