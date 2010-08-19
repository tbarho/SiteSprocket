<% if ProjectResults.MoreThanOnePage %>
<ul class="pagination cf">
	<% if ProjectResults.NotFirstPage %>
		<li><a class="previous" href="$ProjectResults.PrevLink" title="<% _t('Pagination.VIEWPREV','View the previous page') %>"><% _t('Pagination.PREVIOUSPAGE','Previous Page') %></a></li>
	<% end_if %>
	<% if ProjectResults.NotLastPage %>
		<li><a class="next" href="$ProjectResults.NextLink" title="<% _t('Pagination.VIEWNEXT','View the next page') %>"><% _t('Pagination.NEXTPAGE','Next Page') %></a></li>
	<% end_if %>				
</ul>
<% end_if %>