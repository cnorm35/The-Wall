<?php
	session_start();
?>
<!doctype html>
<html lang="en">
<head>
	<title>THE WALL</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="container">
		<h1>Welcome to THE WALL</h1>
		<?php
			if(isset($_SESSION['errors']))
			{
				foreach($_SESSION['errors'] as $error)
				{
					echo "<p class='text-danger'> {$error} </p>";
				}
				unset($_SESSION['errors']);
			}

			if(isset($_SESSION['success']))
			{
				echo "<p class='text-success'> {$_SESSION['success']} </p>";
			}
			unset($_SESSION['success']);
		?>
		<form class="form-signin" action="process.php" method="post">
			<input type="hidden" name="action" value="register"/>
			<h2 class="form-signin-heading">New Users</h2>
			<input type="text" class="form-control" placeholder="First Name" name="first_name"/>
			<input type="text" class="form-control" placeholder="Last Name" name="last_name"/>
			<input type="text" class="form-control" placeholder="Email address" name="email"/>
			<input type="password" class="form-control" placeholder="Password" name="password"/>
			<input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password"/>
			<input type="submit" class="btn btn-lg btn-primary btn-block" value="Register">
		</form>
		<form class="form-signin" action="process.php" method="post">
			<input type="hidden" name="action" value="login"/>
			<h2 class="form-signin-heading">Existing Users</h2>
			<input type="text" class="form-control" placeholder="Email address" name="email"/>
			<input type="password" class="form-control" placeholder="password" name="password"/>
			<input type="submit" class="btn btn-lg btn-primary btn-block" value="Login">
		</form>
	</div>
</body>
</html>