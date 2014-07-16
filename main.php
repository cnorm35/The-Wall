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
	
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="navbar-header">
				<a class="navbar-brand navbar-left" href="main.php" id="dojo">CodingDojo Wall</a>
				<p class="navbar-text navbar-right" id="welcome">Welcome <?php echo "{$_SESSION['first_name']} <a id='log-out' href='process.php'>Log Out</a></p>";?>

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

				if(isset($_SESSION['comment-errors']))
				{
					foreach ($_SESSION['comment-errors'] as $comment_error) {
						echo "<p class='text-danger'> {$comment_error} </p>";
					}
					unset($_SESSION['comment-errors']);
				}

			?>

		<form action="process.php" method="post" id="message-box">
			<input type="hidden" name="action" value="post-message">
			<h4 class="message-header">Post a message</h4>
			<textarea class="form-control" rows="4"  placeholder="Enter message..." name="message"></textarea>
			<input type="submit" class="btn btn-primary" value="Post a message" id="submit-quote"/>
		</form>

		<?php
			$message_query = "SELECT users.first_name, users.last_name, messages.id, messages.message, messages.created_at, messages.users_id
						FROM messages JOIN users ON messages.users_id = users.id ORDER BY created_at DESC";
			$messages = fetch_all($message_query);
			
			foreach ($messages as $message) 
			{
				// var_dump($message);
				$message_date = strtotime($message['created_at']);
				$now = time();
				// echo $message_date;
				// echo $now;
				$time_diff_in_minutes = ($now - $message_date) / 60;
				// echo $time_diff_in_minutes;
				echo "<p class='message'><strong> {$message['first_name']}" . " " . "{$message['last_name']} " . date('M jS Y', $message_date) ."</strong></p>";
				echo "<p class='message'> {$message['message']} </p>";

				if($message['users_id'] == $_SESSION['user-id'] && $time_diff_in_minutes < 30)
				{
					echo "<form action='process.php' method='post'>
							<input type='hidden' name='action' value='delete'/>
							<input type='hidden' name='delete_message' value='{$message['id']}'/>
							<input type='submit' class='btn btn-danger btn-xs'  id='delete-btn' value='Delete'/>
						  </form> ";

				}

				$comments_query = "SELECT comments.messages_id, comments.comment, users.first_name, users.last_name, comments.created_at
									FROM comments
									JOIN users ON comments.users_id = users.id
									WHERE messages_id = {$message['id']}";
				$comments = fetch_all($comments_query);
				foreach ($comments as $comment) {
					$comment_date = strtotime($comment['created_at']);
					echo "<p class='comment'><strong> {$comment['first_name']}" . " " . "{$comment['last_name']} " . date('M jS Y', $comment_date) ."</strong></p>";
					echo "<p class='comment'> {$comment['comment']} </p>"; 
				}

				echo "<form action='process.php' method='post'>
						<input type='hidden' name='action' value='comment'>
						<input type='hidden' name='message_id' value='{$message['id']}'>
						<h5 class='comment-header'>Post a comment</h5>
						<textarea class='form-control' rows='1'  placeholder='Enter comment...' name='comment'></textarea>
						<input type='submit' class='btn btn-success btn-sm' value='Post a comment' id='submit-comment'/>
					</form>";

			}


		?>

		</div>
</body>
</html>

