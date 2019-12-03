<?php
	require('db_connect.php');
	
	
	if (isset($_POST['register']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['reenter'])){
	
	//  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$reenter = filter_input(INPUT_POST, 'reenter', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
		$query1 = "SELECT email FROM accounts WHERE email = :email";
		$statement1 = $db->prepare($query1);
		
		$statement1->bindValue(':email', $email, PDO::PARAM_STR);
		$statement1->execute();
		$row = $statement1->fetch();
		
		if ($row < 1){
			if ($password == $reenter){
				//  Build the parameterized SQL query and bind to the above sanitized values.
				$query = "INSERT INTO accounts (email, password) VALUES (?, ?)";
				$statement = $db->prepare($query);
				
				//  Bind values to the parameters
				$statement->bindValue(1, $email);
				$statement->bindValue(2, $password);
				
				if($statement->execute()){
					echo "Success";
					echo "<p>Return to <a href='homepage.php'>Homepage</a></p>";
					exit;
				}
			} else {
				echo "Re-entered password is not the same as password.";
				echo "<p><a href='register.html'>Try again</a></p>";
				exit;
			}
		} else {
			echo "Account name has been used, create a different email.";
			echo "<p><a href='register.html'>Try again</a></p>";
			exit;
		}
	}
?>