<h2><% _t('SSP.YOURPROJECTS','Your Projects') %></h2>
<div>
	<a href="$Link(order)"><% _t('SSP.NEWPROJECT','New project') %></a> 
	<a href="$Link(profile)"><% _t('SSP.EDITPROFILE','Edit profile') %></a> 
	<a href="/Security/logout"><% _t('SSP.LOGOUT','Logout') %></a> 	
</div>
<table class="project_results" width="90%" border="1" cellspacing="0" cellpadding="0">
	<% include ProjectResults %>
</table>