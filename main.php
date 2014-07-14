<?php
session_start();
require_once('new-connection.php');
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
	
		<nav class="navbar navbar-default" role="navigation">
			<div class="navbar-header">
				<a class="navbar-brand" href="main.php">CodingDojo Wall</a>
				<p class="navbar-text navbar-right">Welcome <?php echo $_SESSION['first_name'];?> <a href="process.php">Log Out</a></p>
			</div>
		</nav>
		<div class="container">
			<?php
				if(isset($_SESSION['message-errors']))
				{
					foreach ($_SESSION['message-errors'] as $error) 
					{
						echo "<p class='text-danger'> {$error} </p>";
					}
					unset($_SESSION['message-errors']);
				}

			?>

		<form action="process.php" method="post">
			<input type="hidden" name="action" value="post-message">
			<h4 class="message-header">Post a message</h4>
			<textarea class="form-control" rows="4"  placeholder="Enter message..." name="message"></textarea>
			<input type="submit" class="btn btn-primary" value="Post a message" id="submit-quote"/>
		</form>

		<?php
			$query = "SELECT users.first_name, users.last_name, messages.message, messages.created_at
						FROM messages JOIN users ON messages.users_id = users.id GROUP BY created_at DESC";
			$messages = fetch_all($query);
			
			foreach ($messages as $message) 
			{
				$date = strtotime($message['created_at']);
				echo "<p class='message'> {$message['first_name']}" . " " . "{$message['last_name']} " . date('M jS Y', $date) ."</p>";
				echo "<p class='message'> {$message['message']} </p>";
			}

		?>

		</div>
</body>
</html>

