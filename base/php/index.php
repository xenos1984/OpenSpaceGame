<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>OpenSpaceGame</title>
	</head>
	<body>
		<h1>OpenSpaceGame</h1>
		<form action="login.php" method="post">
			<fieldset>
				<legend>Login</legend>
				<table>
					<tr><th>Username:</th><td><input type="text" name="nick" maxlength="32"></td></tr>
					<tr><th>Passwort:</th><td><input type="password" name="pass" maxlength="32"></td></tr>
					<tr><td colspan="2"><input type="reset" value="Clear"><input type="submit" value="Login"></td></tr>
				</table>
			</fieldset>
		</form>
		<form action="register.php" method="post">
			<fieldset>
				<legend>Register</legend>
				<table>
					<tr><th>Username:</th><td><input type="text" name="nick" maxlength="32"></td></tr>
					<tr><th>Passwort:</th><td><input type="password" name="pass1" maxlength="32"></td></tr>
					<tr><th>Confirm passwort:</th><td><input type="password" name="pass2" maxlength="32"></td></tr>
					<tr><th>E-mail:</th><td><input type="text" name="email" maxlength="64"></td></tr>
					<tr><td colspan="2"><input type="reset" value="Clear"><input type="submit" value="Register"></td></tr>
				</table>
			</fieldset>
		</form>
	</body>
</html>