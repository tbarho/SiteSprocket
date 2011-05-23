					<div class="paging">  
						<% if PrevLink %>
							<a href="$PrevLink" title="<% _t('SSPAdmin.PREVIOUS','Previous') %>"><% _t('SSPAdmin.PREVIOUS','Previous') %></a>
						<% end_if %>
						<% if NextLink %>
							<a href="$NextLink" title="<% _t('SSPAdmin.NEXT','NEXT') %>"><% _t('SSPAdmin.NEXT','Next') %></a>
						<% end_if %>
					</div>
					<table cellpadding="0" cellspacing="0" class="project_results">
						<thead>
							<tr>
								<% control Headings %>
									<th>
										<div>
											<a href="$SortLink" class="<% if Sorted %>sorted $SortDir<% end_if %>">$Label</a>
										</div>
									</th>
								<% end_control %>
							</tr>
						</thead>
						<tbody>
							<% control ProjectResults %>
								<tr<% if Even %> class="even"<% end_if %>>
									<% control Fields %>
										<% if Pos = 1  %>
										<td class="first"<% if UnreadClient %> style="font-weight: bold"<% end_if %>>
											<a href="$EditLink">$Value - $Title &raquo;</a>
										</td>
										<% end_if %>
										
										<% if Pos = 2 %>
										<td<% if UnreadClient %> style="font-weight: bold"<% end_if %>>
											$LastEdited
										</td>
										<% end_if %>
										
										<% if Pos = 3 %>
											<td<% if UnreadClient %> style="font-weight: bold"<% end_if %>>$Value</td>
										<% end_if %>
									<% end_control %>
								</tr>
							<% end_control %>
						</tbody>
					</table>
