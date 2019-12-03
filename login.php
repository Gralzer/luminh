<?php
	require('db_connect.php');
	
	if (isset($_POST['login']) && !empty($_POST['email']) && !empty($_POST['password'])){
	
	//  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
        //  Build the parameterized SQL query and bind to the above sanitized values.
		$query = "SELECT email, password FROM accounts WHERE email = :email AND password = :password";
        $statement = $db->prepare($query);
		
        //  Bind values to the parameters
		$statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':password', $password, PDO::PARAM_STR);
		
		$statement->execute();
		$row = $statement->fetch();

		if($row > 0){
			echo "Success";
			echo "<p>Return to <a href='homepage.php?account=" . $_POST['email'] . "'>Homepage</a></p>";
			exit;
		} else {
			echo "Login failed, email and/or password was incorrect.";
			echo "<p><a href='login.html'>Try again</a></p>";
			exit;
		}
	}
?>