<div class="search-form">$SearchForm</div>
<h3>$Heading</h3>
<% if ProjectResults %>
	<div class="projects">
		<table class="project_results" cellspacing="0" cellpadding="0">
			<% include ProjectResults %>
		</table>
	</div>
<% else %>
	<p class="no-results"><% _t('SSPAdmin.NOPROJECTS','There are no projects that meet your criteria') %></p>
<% end_if %>