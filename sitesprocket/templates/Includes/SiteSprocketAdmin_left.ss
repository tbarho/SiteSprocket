<h2><% _t('SSPAdmin.PROJECTSNAV','Projects') %></h2>
<div id="treepanes">
	<ul id="projecttree" class="tree">
		<li id="record-root" class="Root last">
			<span class="a Root last">
				<span class="b">
					<span class="c"><a href="#"><strong>Select a View:</strong></a></span>
				</span>
			</span>
			<ul>
			<% if CurrentMember.isAdmin %>
				<li class="Group"><span class="a Group"><span class="b"><span class="c"><a href="$Link(allprojects)?reset=1" rel="right"><% _t('SSPAdmin.ALLPROJECTS','All projects') %></a></span></span></span></li>
				<li class="Group"><span class="a Group"><span class="b"><span class="c"><a href="$Link(openprojects)?reset=1" rel="right"><% _t('SSPAdmin.OPENPROJECTS','Open projects') %> ($OpenProjectsCount.Formatted)</a></span></span></span></li>
				<li class="Group"><span class="a Group"><span class="b"><span class="c"><a href="$Link(closedprojects)?reset=1" rel="right"><% _t('SSPAdmin.CLOSEDPROJECTS','Closed projects') %> ($ClosedProjectsCount.Formatted)</a></span></span></span></li>
			<% end_if %>
				<li class="Group"><span class="a Group"><span class="b"><span class="c"><a href="$Link(unassignedprojects)?reset=1" rel="right"><% _t('SSPAdmin.UNASSIGNEDPROJECTS','Unassigned') %> ($UnassignedProjectsCount.Formatted)</a></span></span></span></li>
				<li class="Group"><span class="a Group"><span class="b"><span class="c"><a href="$Link(myprojects)?reset=1" rel="right"><% _t('SSPAdmin.MYPROJECTS','My projects') %> ($MyProjectsCount.Formatted)</a></span></span></span></li>
				<li class="Group"><span class="a Group"><span class="b"><span class="c"><a href="$Link(closedbyme)?reset=1" rel="right"><% _t('SSPAdmin.CLOSEDBYME','Closed by me') %> ($ClosedByMeCount.Formatted)</a></span></span></span></li>
			</ul>
		</li>
	</ul>
</div>