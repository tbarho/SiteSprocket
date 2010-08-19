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
				
				<div id="slider">
					<ul class="navigation">
						<% control Features %>
						<li><a href="#$Title">$Heading</a></li>
						<% end_control %>
					</ul>
					
					<!-- element with overflow applied -->
					<div class="scroll">
						<!-- the element that will be scrolled during the effect -->
						<div class="scroll-container">
							<!-- our individual panels -->
							<% control Features %>
							<div class="panel" id="$Title">
								<h3>$Heading</h3>
								$Content
							</div>
							<% end_control %>
						</div>
					</div>
				</div>
				
				
				<p>Here is some content that lets you read more about <a href="#flexible-design">Flexible Design</a></p>
				
				
				
<!--
				<div class="features">
				
				<% control Features %>
								
				<div class="section <% if Odd %>left<% else %>right<% end_if %>">
					<a class="featurebox" href="$Image.Url">$Image.CroppedImage(405,445)</a>
					<h3>$Heading</h3>
					$Content
				</div>
				
				<% end_control %>
				
				</div>
-->
				$Form
				$PageComments
			</div>
