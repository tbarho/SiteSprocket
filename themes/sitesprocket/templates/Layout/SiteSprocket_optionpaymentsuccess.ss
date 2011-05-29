			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1>
						Thank you for your payment!
						</h1>
					</div>
				</div>
			</div>
			<div id="main">
				<h2>Here's what you paid for:</h2>
				
				<p>
					<% control Option %>
						You paid for the option "$Description" and it cost you <strong>$Cost.Nice</strong>
					<% end_control %>
				</p>
					
				<h3><a href="$Link(projects)"><% _t('SSP.GOTOPROJECTS','Go to your projects') %></a></h3>
			</div>
