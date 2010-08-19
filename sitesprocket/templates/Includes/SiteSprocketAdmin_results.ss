$SearchForm
<h3>$Heading</h3>
<% if ProjectResults %>
	<table class="project_results" width="90%" border="1" cellspacing="0" cellpadding="0">
		<% include ProjectResults %>
	</table>
<% else %>
	<% _t('SSPAdmin.NOPROJECTS','There are no projects that meet your criteria') %>
<% end_if %>