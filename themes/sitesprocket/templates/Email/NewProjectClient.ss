<html>
<head>
</head>
<body>
<% control Project %>
<p>Dear $Creator.FirstName,</p>
<% end_control %>

<p>Thank you for your order from SiteSprocket! To view your project, follow the link below.</p>

<p><a href="$Project.EmailEditLink">{$BaseHref}$Project.EmailEditLink</a></p>

<p>Sincerely,<br />SiteSprocket</p>

</body>
</html>