			<% require themedCSS(page-projects) %>
			
			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1>
						Subscriptions
						</h1>
						<ul id="sub-nav">
							<li><a href="$Link(profile)"><% _t('SSP.EDITPROFILE','Edit profile') %></a></li>
							<li><a href="/Security/logout"><% _t('SSP.LOGOUT','Logout') %></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div id="main">
				<h2>Your Subscriptions</h2>
				<div class="projects">
					<table>
						<thead>
							<tr>
								<td>ID</td>
								<td>ProjectID</td>
								<td>URL</td>
								<td>Status</td>
								<td></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1234</td>
								<td>5678</td>
								<td>jimmykung.com</td>
								<td>Inactive</td>
								<td><a href="#">&raquo;</a></td>
							</tr>
							<tr>
								<td>2234</td>
								<td>5679</td>
								<td>kungpaomeow.com</td>
								<td>Active</td>
								<td><a href="#">&raquo;</a></td>
							</tr>
							<tr>
								<td>3234</td>
								<td>5680</td>
								<td>jimmycrackcorn.sitesprocket.com</td>
								<td>Active</td>
								<td><a href="#">&raquo;</a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
