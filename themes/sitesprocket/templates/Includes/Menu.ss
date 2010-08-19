					<ul id="nav">
						<% control Menu(1) %>
						<li>
							<a href="$Link"<% if LinkOrSection = section %> class="active"<% end_if %>>
								<span class="l">&nbsp;</span>
								<span class="c">$MenuTitle.XML</span>
								<span class="r">&nbsp;</span>
								<span class="arrow">&nbsp;</span>
							</a>
						</li>
						<% end_control %>
					</ul>