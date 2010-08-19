$SearchForm
<% if Project %>
	<div id="project" class="{'id' : '$Project.ID'}">
		<div><a rel="right" href="$Link(results)"><% _t('SSPAdmin.BACK','Back') %></a></div>
		<div style="width:500px;float:left;margin-right:20px;">
			<% control Project %>
				<h2>$Title</h2>
				<div id="messages">
					<% include Messages %>
				</div>
			<% end_control %>
	
		</div>
		<div style="width:200px;float:left;">
			<div id="update_message"></div>
			<% if CurrentMember.isAdmin %>
				$CSRDropdown
			<% end_if %>
			$StatusDropdown
			<% if Project.OtherProjects %>
				<h3><% _t('SSPAdmin.OTHERPROJECTS','Other projects for this user') %></h3>
				<% control Project %>
					<ul>
					<% control OtherProjects %>
						<li><a href="$EditLink" rel="right">$Title</a></li>
					<% end_control %>
					</ul>
				<% end_control %>
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