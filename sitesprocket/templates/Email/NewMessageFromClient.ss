<html>
<head>
</head>
<body>
<% control Project %>
<p>Dear $CSR.FirstName,</p>
<% end_control %>

<p>A client a new message on the project "$Project.Title". To view the message, follow the link below.</p>

<p><a href="$Project.EmailEditLink">{$BaseHref}$Project.EmailEditLink</a></p>

<p>Sincerely,<br />SiteSprocket</p>

</body>
</html>