<?php
	require('db_connect.php');

	// Build and prepare SQL String with :id placeholder parameter.
    $query = "SELECT champions.*, images.file FROM champions LEFT JOIN images ON champions.name = images.champ WHERE champions.id = :id LIMIT 1";
    $statement = $db->prepare($query);
    
    // Sanitize $_GET['id'] to ensure it's a number.
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    
	if (!isset($_POST['back'])) {
		if (filter_var($id, FILTER_VALIDATE_INT)){
			// Bind the :id parameter in the query to the sanitized
			// $id specifying a binding-type of Integer.
			$statement->bindValue('id', $id, PDO::PARAM_INT);
			$statement->execute();
			
			// Fetch the row selected by primary key id.
			$row = $statement->fetch();
		} else {
			echo "Invalid id.";
			echo "<p>Return to <a href='homepage.php'>Homepage</a></p>";
			exit;
		}
	}
	if (isset($_POST['back'])) {
		header('location: champions.php');
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Champions</title>
</head>
<body>
	<div>
		<div>
			<ul>
				<li><a href="homepage.php">Home</a></li>
				<?php
					$query1 = 'SELECT * FROM menu';
					$statement1 = $db->prepare($query1);
					$statement1->execute();
					while ($row1 = $statement1->fetch()): 
				?>
					<li><a href="<?= $row1['link']?>"><?= $row1['category']?></a></li>
				<?php endwhile?>
				<li><small><a href="createupdate.html">Update</a></small></li>
			</ul>
		</div>
		
		<div>
			<h1>Champion <?= $row['name']?></h1>
		</div>
		
		<div>
			<p><img src="uploads/<?= $row['file']?>" height="180" width="130" onerror=this.src="uploads/default.jpg"></p>
			<p>Name: <?= $row['name']?></p>
			<p>Type: <?= $row['type']?></p>
			<p>Style: <?= $row['style']?></p>
			<p>Difficulty: <?= $row['difficulty']?></p>
			<p>Strength: <?= $row['strength']?></p>
		</div>
		
		<div>
			<form action="viewchamp.php" method="post">
				<p><input type="submit" name="back" value="Return"/></p>
			</form>
		</div>
		
		<div>
			<p>Comment section</p>
		</div>
	</div>
</body>
</html>