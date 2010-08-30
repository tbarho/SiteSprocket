			<% require themedCSS(page-profile) %>
			
			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1>
							<% _t('SSP.PROFILEFORM','Edit profile') %>
						</h1>
						<ul id="sub-nav">
							<li><a href="$Link(projects)"><% _t('SSP.YOURPROJECTS','Projects & Tickets') %></a></li>
							<li><a href="/Security/logout"><% _t('SSP.LOGOUT','Logout') %></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div id="main">
				$ProfileForm
			</div>
