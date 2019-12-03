<?php
	require('db_connect.php');
	
	
	if (isset($_POST['update']) && !empty($_POST['category']) && !empty($_POST['link'])){
		
		//  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $link = filter_input(INPUT_POST, 'link', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
		if (!empty($_POST['old'])){
			$old = filter_input(INPUT_POST, 'old', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			
			$query1 = "SELECT category FROM menu WHERE category = :category";
			$statement1 = $db->prepare($query1);
			
			$statement1->bindValue(':category', $old, PDO::PARAM_STR);
			$statement1->execute();
			$row = $statement1->fetch();
			
			if (empty($row)){
				echo "No matched category.";
				echo "<p><a href='createupdate.html'>Try again</a></p>";
				exit;
			} else {
				$query = "UPDATE `menu` SET `category`= ?,`link`= ? WHERE category = ?";
				$statement = $db->prepare($query);
						
				//  Bind values to the parameters
				$statement->bindValue(1, $category);
				$statement->bindValue(2, $link);
				$statement->bindValue(3, $old);
				
				if($statement->execute()){
					echo "Success";
					header('Location: homepage.php');
					exit;
				} else {
					echo "New category already exists.";
					echo "<p><a href='createupdate.html'>Try again</a></p>";
					exit;
				}
			}
		} else {
			$query = "INSERT INTO menu (category, link) VALUES (?, ?)";
			$statement = $db->prepare($query);
					
			//  Bind values to the parameters
			$statement->bindValue(1, $category);
			$statement->bindValue(2, $link);
		
			if($statement->execute()){
				echo "Success";
				header('Location: homepage.php');
				exit;
			} else {
				echo "Category already exists.";
				echo "<p><a href='createupdate.html'>Try again</a></p>";
				exit;
			}
		}
	}
?>