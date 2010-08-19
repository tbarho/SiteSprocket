			<% require themedCSS(page-projects) %>
			
			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1>
						Projects & Tickets
						</h1>
						<ul id="sub-nav">
							<li><a href="$Link(profile)"><% _t('SSP.EDITPROFILE','Edit profile') %></a></li>
							<li><a href="/Security/logout"><% _t('SSP.LOGOUT','Logout') %></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div id="main">
				<h2><% _t('SSP.YOURPROJECTS','Your Projects') %></h2>
				<div class="projects">
					<% include ProjectResults %>
				</div>
				<div class="order">
					<a href="$Link(order)"><% _t('SSP.NEWPROJECT','New project') %></a>
				</div>
			</div>
