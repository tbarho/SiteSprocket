<h3><% _t('SSPAdmin.PROJECTSNAV','Projects') %></h3>
<ul>
<% if CurrentMember.isAdmin %>
	<li><a href="$Link(allprojects)?reset=1" rel="right"><% _t('SSPAdmin.ALLPROJECTS','All projects') %></a></li>
	<li><a href="$Link(openprojects)?reset=1" rel="right"><% _t('SSPAdmin.OPENPROJECTS','Open projects') %></a> ($OpenProjectsCount.Formatted)</li>
	<li><a href="$Link(closedprojects)?reset=1" rel="right"><% _t('SSPAdmin.CLOSEDPROJECTS','Closed projects') %></a> ($ClosedProjectsCount.Formatted)</li>
<% end_if %>
	<li><a href="$Link(unassignedprojects)?reset=1" rel="right"><% _t('SSPAdmin.UNASSIGNEDPROJECTS','Unassigned') %></a> ($UnassignedProjectsCount.Formatted)</li>
	<li><a href="$Link(myprojects)?reset=1" rel="right"><% _t('SSPAdmin.MYPROJECTS','My projects') %></a> ($MyProjectsCount.Formatted)</li>
	<li><a href="$Link(closedbyme)?reset=1" rel="right"><% _t('SSPAdmin.CLOSEDBYME','Closed by me') %></a> ($ClosedByMeCount.Formatted)</li>
</ul>
