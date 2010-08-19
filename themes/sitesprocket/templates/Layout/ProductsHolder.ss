			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<% if PageDescription %>
						<h1 class="desc">$Title</h1>
						<h2 class="desc">$PageDescription</h2>
						<% else %>
						<h1>$Title</h1>
						<% end_if %>
					</div>
				</div>
			</div>
			<div id="main">
				<div class="breadcrumbs">
					<span>You are here:</span> $BreadCrumbs
				</div>
				<% if Children %>
				<ul class="content-list">
					<% control Children %>
					<li>
						<h3><a href="$Link">$MenuTitle.XML</a></h3>
						<p>$ProductDescription</p>
					</li>
					<% end_control %>
				</ul>
				<% end_if %>
				$Content
				$Form
				$PageComments
			</div>





