					<div id="messages">
					
					<% control Messages %>

					
						<div class="project_messages">
							<div class="post">
								<div class="<% if You %>you<% else %>admin<% end_if %>">
									<div class="title">
										<strong><% if You %><% _t('SSP.YOU','You') %><% else %>$Author.FirstName<% end_if %></strong> said on $Created.Format(m-d-Y) at $Created.Format(h:i a)
									</div>
									<div class="body">
										<div class="notes">
											$MessageText
										</div>
										<% if Attachments %>
										<ul class="uploads">
											<% control Attachments %>
											<li><img src="$Icon" alt="" /><em>$Name</em> <% _t('SSP.ATTACHED','uploaded') %> (<a href="$URL">download</a>)</li>
											<% end_control %>
										</ul>
										<% end_if %>
										<% if PaymentOption %>
										<div class="payment">
											<% control PaymentOption %>
												<% if Paid %>
												Added $Description for $Cost.Nice
												<% else %>
												<a href="$PayLink">Add $Description for $Cost.Nice</a>
												<% end_if %>
											<% end_control %>
										</div>
										<% end_if %>
									</div>
								</div>
							</div>
						</div>					
					<% end_control %>

					</div>	
