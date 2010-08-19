			<% require themedCSS(page-projects) %>
			
			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1>
						Projects & Tickets
						</h1>
						<ul id="sub-nav">
							<li><a href="#">Edit Your Profile</a></li>
							<li><a href="#">Logout</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div id="main">
				<h2>Your Projects</h2>
				<div class="projects">
					<div class="paging">View  
						<select name="select" id="select">
							<option value="10" selected="selected">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="40">40</option>
							<option value="100">100</option>
						</select>   per page</div>
					<table cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th><div>
									<a href="#">Project Number - Subject</a>
								</div></th>
								<th><div>
									<a href="#">Last Updated</a>
								</div></th>
								<th><div>
									<a href="#">Status</a>
								</div></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><a href="/sample-project/#recent-post">1234 - IAmAwesome.com &raquo;</a></td>
								<td>23-Jun-10 10:42am</td>
								<td>Open</td>
							</tr>
							<tr class="even">
								<td><a href="/sample-project">1345 - YouRockz.com &raquo;</a></td>
								<td>24-May-10 11:45pm</td>
								<td>Closed</td>
							</tr>
							<tr>
								<td><a href="/sample-project">1456 - CanWeGetA.com &raquo;</a></td>
								<td>14-Apr-10 9:35am</td>
								<td>Closed</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="order">
					<a href="/order"><input id="order-btn" class="order-btn" type="image" value="Submit" src="themes/sitesprocket/images/place-new-order.png" alt="Order Now!" /></a>
				</div>
			</div>
