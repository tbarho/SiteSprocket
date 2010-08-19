<% require css(sitesprocket/css/fancybox.css) %>
<% if Project %>
<% control Project %>
	<h2><% sprintf(_t('SSP.EDITINGPROJECT','Editing Project: %s'),$Title) %></h2>
	<div><a href="#invoice" rel="fb"><% _t('SSP.VIEWINVOICE','View invoice') %></a></div>
	
	<div><% _t('SSP.ORDERDATE','Ordered on') %>: $Created.Format(m-d-Y) <% _t('SSP.ORDERTIME','at') %> $Created.Format(h:i a)</div>
	<div id="messages">
		<% include ClientMessages %>
	</div>
	<div class="more-box" id="invoice">
		<% include Invoice %>	
	</div>
<% end_control %>
<h3><% _t('SSP.POSTREPLY','Post a reply') %></h3>
$CreateMessageForm
<% else %>
	<h2><% _t('SSP.PROJECTNOTFOUND','Project not found') %></h2>
<% end_if %>