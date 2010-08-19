<h3><% _t('SSP.HAVEACCOUNT','Have an account?') %></h3>
$LoginForm
<h3><% _t('SSP.NEWACCOUNT','New here? Create one now') %></h3>
$AccountForm
<div id="price-info" style="position:fixed;top:20px;left:800px;">
	<table cellspacing="0" cellpadding="0" border="1" width="300">
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