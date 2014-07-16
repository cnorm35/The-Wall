<?php
	session_start();
	require('new-connection.php');

	//register new user
	if(isset($_POST['action']) && $_POST['action'] == 'register')
	{
		register_user($_POST);
	}

	//login existing user
	elseif (isset($_POST['action']) && $_POST['action'] == 'login') 
	{
		login_user($_POST);
	}
	//post new message
	elseif (isset($_POST['action']) && $_POST['action'] == 'post-message')
	{
		post_message($_POST);
	}
	//post new comment 
	elseif (isset($_POST['action']) && $_POST['action'] == "comment")
	{
		post_comment($_POST);
	}
	elseif (isset($_POST['action']) && $_POST['action'] == 'delete')
	{
		delete_message($_POST);
	}
	else 
	{
		session_destroy();
		header('location: index.php');
		die();
	}








//-------------------Register New User-----------------------------//
	function register_user($post)
	{
		//validation checks
		$_SESSION['errors'] = array();

		if(empty($post['first_name']))
		{
			$_SESSION['errors'][] = "First name cannot be blank.";
		}
		if(empty($post['last_name']))
		{
			$_SESSION['errors'][] = "Last name cannot be blank";
		}
		if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
		{
			$_SESSION['errors'][] = "Please enter a valid email address";
		}
		if(empty($post['password']))
		{
			$_SESSION['errors'][] = "Please enter a password.";
		}
		if($post['password'] != $post['confirm_password'])
		{
			$_SESSION['errors'][] = "Passwords do not match.";
		}

		//if there are errors present
		if(count($_SESSION['errors']) > 0)
		{
			header('location: index.php');
			die();
		}
		else 
		{
			//escape string to protect against sql injection.
			$esc_first_name = escape_this_string($post['first_name']);
			$esc_last_name = escape_this_string($post['last_name']);
			$esc_email = escape_this_string($post['email']);
			$esc_password = escape_this_string($post['password']);

			//code to add new user into db
			$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at)
						VALUES ('{$esc_first_name}', '{$esc_last_name}', '{$esc_email}', '{$esc_password}', NOW(), NOW())";
			run_mysql_query($query);
			//with new user created add success message to show up on index.php
			$_SESSION['success'] = "User successfully created!";
			header('location: index.php');
			die();

		}

	}


	function login_user($post)
	{
		$query = "SELECT * FROM users WHERE users.email='{$post['email']}' AND users.password = '{$post['password']}'";
		
		$user = fetch_all($query);

		if(count($user) > 0)
		{
			// var_dump($user);
			$_SESSION['user-id'] = $user[0]['id']; //first user returned, first index, set to users.id
			$_SESSION['first_name'] = $user[0]['first_name'];
			$_SESSION['logged_in'] = TRUE; //bool showing that user is logged in.
			header('location: main.php');
			die();
		}
		else
		{
			//if no user is found, add error into #_SESSION['errors'] redirect back to index
			$_SESSION['errors'][] = "No user found, please try again";
			header('location: index.php');
			die();
		}
	}

	function post_message($post)
	{
		$_SESSION['message-errors'] = array();

		if(empty($post['message']))
		{
			$_SESSION['message-errors'][] = "Your message cannot be left blank";
		}

		if(count($_SESSION['message-errors']) > 0)
		{
			header('location: main.php');
			die();
		}
		else
		{
			//esc message before loaded into the database.
			$esc_message = escape_this_string($post['message']);
			$query = "INSERT INTO messages (users_id, message, created_at, updated_at)
					VALUES ('{$_SESSION['user-id']}', '{$esc_message}', NOW(), NOW())";
			run_mysql_query($query);
			header('location: main.php');
			die();
		}
	}

	function post_comment($post)
	{
		$_SESSION['comment-errors'] = array();

		if(empty($post['comment']))
		{
			$_SESSION['comment-errors'][] = "Comment cannot be left blank.";
		}

		if(count($_SESSION['comment-errors']) > 0)
		{
			header('location: main.php');
			die();
		}
		else
		{
			//escape comments before going into db
			//enter query to post comment into db.
			$esc_comment = escape_this_string($post['comment']);
			$query = "INSERT INTO comments (users_id, messages_id, comment, created_at, updated_at)
						VALUES ({$_SESSION['user-id']}, {$post['message_id']}, '{$esc_comment}', NOW(), NOW())";
			// echo $query;
			run_mysql_query($query);
			header('location: main.php');
			die();

		}
	}

	function delete_message($post)
		{
			$query = "DELETE FROM messages where id = {$post['delete_message']}";
			// var_dump($_POST['delete_message']);
			run_mysql_query($query);
			header('location: main.php');
		}












?>