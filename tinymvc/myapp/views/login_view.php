

<html>
	<head>
		<title>Library Login</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
		
		<link rel="stylesheet" href="/css/site.css" type="text/css" />
<!-- 		<link rel="stylesheet" href="/~svoznyuk/library/htdocs/css/site.css" type="text/css" /> -->
	</head>
	<body>
		<div class="wrapper">
			<h2>Wecome to the Library</h2>
			<h4>Please sign in</h4>
			<div id="login">
				<form method="post" action="/index.php/default/login" id="login-form">
					<div>
						<label for="u-name">User Name:</label>
						<input type="text" name="u_name" id="u-name" value="" />
					</div>
					<div>
						<label for="u-password">Password:</label>
						<input type="password" name="u_pass" id="u-password" />
					</div>
					<div id='submit'>
						<input type="submit" name="sign_in" value="Sign In" />
					</div>
				</form>
			</div>
		</div>
	</body>
</html>