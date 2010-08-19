			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1>
						<% if ParentClassName != HomePage %>
						This ain't no home page, it's a $ClassName, and it's parent is a $ParentClassName
						<% else %>
						This IS a home page, 'cause $Parent.Title is it's parent!
						<% end_if %>
						</h1>
					</div>
				</div>
			</div>
			<div id="main">
				<% control Parent %>
				<% if ClassName = HomePage %>
				<% control Children %>
					<p>
						<a href="$Link">$Title<% if LinkOrCurrent = current %> - Current<% end_if %></a>
					</p>
				<% end_control %>
				<% end_if %>
				<% end_control %>
				$Content
				$Form
				$PageComments
			</div>
