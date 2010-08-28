$SearchForm
<% if Project %>
	<div id="project" class="{'id' : '$Project.ID'}">
		<div><a rel="right" href="$Link(results)"><% _t('SSPAdmin.BACK','Back') %></a></div>
		<div class="project-left">
			<% control Project %>
				<h2>$Title</h2>
				<div id="messages">
					<% include Messages %>
				</div>
			<% end_control %>
	
		</div>
		<div class="project-right">
			<div id="update_message"></div>
			<% if CurrentMember.isAdmin %>
			<div class="assign">
				<h3>Assign To:</h3>	
				$CSRDropdown
			</div>
			<% end_if %>
			<div class="status">
				<h3>Update Status:</h3>
				$StatusDropdown
			</div>
			<% if Project.OtherProjects %>
				<div class="other-projects">
				<h3><% _t('SSPAdmin.OTHERPROJECTS','Other projects for this user') %></h3>
				<% control Project %>
					<ul>
					<% control OtherProjects %>
						<li><a href="$EditLink" rel="right">$Title</a></li>
					<% end_control %>
					</ul>
				<% end_control %>
				</div>
			<% end_if %>
		</div>
		<div style="clear:both;"></div>
		<div id="add_message">
			<h3><% _t('SSPAdmin.ADDMESSAGE','Add message') %></h3>
			$CreateMessageForm
		</div>
	</div>
<% else %>
	<% _t('SSPAdmin.PROJECTNOTFOUND','That project could not be found') %>
<% end_if %>