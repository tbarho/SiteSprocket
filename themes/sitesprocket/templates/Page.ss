<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<% base_tag %>
	<title><% if MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> - $SiteConfig.Title</title>
	$MetaTags(false)
	<link rel="shortcut icon" href="/favicon.ico" />
	
	<% require themedCSS(all) %>
	<% require themedCSS(add) %>
	<% require themedCSS(add-forms) %>
	
	<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="css/lt8.css" media="screen"/><![endif]-->
	<!--[if lt IE 7]><script src="js/lt7.js" type="text/javascript"></script><![endif]-->
</head>
<body>
<div class="w1">
	<div id="wrapper">
		<div class="w2">
			<div id="header">
				<div class="holder">
					<strong class="logo"><a href="$BaseUrl">Site Sprocket Fast, friendly websites.</a></strong>
					<% include Menu %>
				</div>
			</div>
			$Layout
		</div>
	</div>
	<% include Footer %>
	</div>
</body>
</html>
