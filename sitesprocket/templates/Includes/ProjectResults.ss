<thead>
	<tr>
		<% control Headings %>
			<th><a href="$SortLink" class="<% if Sorted %>sorted $SortDir<% end_if %>">$Label</a></th>
		<% end_control %>
	</tr>
</thead>
<tbody>
	<% control ProjectResults %>
		<tr>
			<% control Fields %>
				<td><a href="$EditLink" rel="right">$Value</a></td>
			<% end_control %>
		</tr>
	<% end_control %>
</tbody>
<tfoot>
	<tr>
		<td colspan="$Headings.Count">
			$PerPageDropdown
			<% if PrevLink %>
				<a href="$PrevLink" title="<% _t('SSPAdmin.PREVIOUS','Previous') %>"><% _t('SSPAdmin.PREVIOUS','Previous') %></a>
			<% end_if %>
			<% if NextLink %>
				<a href="$NextLink" title="<% _t('SSPAdmin.NEXT','NEXT') %>"><% _t('SSPAdmin.NEXT','Next') %></a>
			<% end_if %>
		</td>
	</tr>
</tfoot>

