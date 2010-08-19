			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<% if PageDescription %>
						<h1 class="desc">$Title</h1>
						<h2 class="desc">$PageDescription</h2>
						<% else %>
						<h1>$Title</h1>
						<% end_if %>
						<% if Children %>
						<ul id="sub-nav">
							<% control Children %>
							<li><a href="$Link"<% if LinkOrCurrent = current %> class="active"<% end_if %>>$MenuTitle.XML</a></li>
							<% end_control %>
						</ul>
						<% else %>
						<% control Parent %>
						<ul id="sub-nav">
							<% control Children %>
							<li><a href="$Link"<% if LinkOrCurrent = current %> class="active"<% end_if %>>$MenuTitle.XML</a></li>
							<% end_control %>
						</ul>
						<% end_control %>						
						<% end_if %>
					</div>
				</div>
			</div>
			
			<div id="main">
				<div class="breadcrumbs">
					<span>You are here:</span> $BreadCrumbs
				</div>
				$Content
				$Form
				$PageComments
			</div>
