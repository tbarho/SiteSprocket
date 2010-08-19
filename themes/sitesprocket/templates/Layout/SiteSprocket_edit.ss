			<% require themedCSS(page-projectdetails) %>
			<% require css(sitesprocket/css/fancybox.css) %>
			
			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1>
						Projects & Tickets
						</h1>
						<ul id="sub-nav">
							<li><a href="$Link(profile)">Edit Your Profile</a></li>
							<li><a href="/Security/logout">Logout</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div id="main">
				<div class="content">
					<div class="crumbs">
						<a href="$Link(projects)">&laquo; Back to Your Projects</a>
					</div>
					<% if Project %>
					<% control Project %>
					<div class="head">
						<strong>Order $ID<% sprintf(_t('SSP.EDITINGPROJECT',' - "%s"'),$Title) %></strong>
						<em><% _t('SSP.ORDERDATE','placed on') %> $Created.Format(M-d-Y) <% _t('SSP.ORDERTIME','at') %> $Created.Format(h:i a)</em>
					</div>
					
					<% include ClientMessages %>
					
					<div class="more-box" id="invoice">
						<% include Invoice %>	
					</div>
					<% end_control %>
					<div class="message">
						<div class="post">
							<div class="you">
								<strong><% _t('SSP.POSTREPLY','Post a reply') %></strong>
								$CreateMessageForm
							</div>
						</div>
					</div>					
					<% else %>
						<h2><% _t('SSP.PROJECTNOTFOUND','Project not found') %></h2>
					<% end_if %>
					
					
				</div>
				<div class="sidebar">
					<h3>Est. Delivery</h3>
					<p>Date / Approx. Time</p>
					<h3>Your Invoice</h3>
					<ul>
						<li><a href="#invoice" rel="fb"><% _t('SSP.VIEWINVOICE','View invoice') %></a></li>
					</ul>
				</div>
			</div>
