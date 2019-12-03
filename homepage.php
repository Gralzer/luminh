<?php
	require('db_connect.php');
    

	if (isset($_POST['submit']) && !empty($_POST['search'])){
	$query = 'SELECT champions.id, champions.name, images.file FROM champions LEFT JOIN images ON champions.name = images.champ WHERE name LIKE "%' . $_POST['search'] . '%"';
	
    $statement = $db->prepare($query);
	//exec sql query
    $statement->execute();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Home Page</title>
</head>
<body>
	<div>
		<div>
			<input type="submit" value="Administration" name="admin" onclick=""/>
		</div>
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
			<select onchange="location = this.value;">
				<option selected disabled hidden>Sign in</option>
				<option value="login.html">Login</option>
				<option value="register.html">Register</option>
			</select>
		</div>
		
		<?php if (isset($_GET['account'])): ?>
		<input type="submit" value="Log out" name="logout" onclick="window.location.href = 'homepage.php';"/>
		<?php endif ?>
		
		<div>
			<h1>Welcome to League of Legends</h1>
		</div>
		
		<div>
			<form action="homepage.php" method="post">
				<label for="search">Search a champion</label>
				<input name="search" id="search"/>
				<input type="submit" value="Go" name="submit"/>
			</form>
		</div>
		
		<div id="result">
		<?php if (isset($_POST['submit']) && !empty($_POST['search'])): ?>
			<?php while ($row = $statement->fetch()): ?>
				<h2>
					<p><a href="viewchamp.php?id=<?= $row['id']?>"><img src="uploads/<?= $row['file']?>" height="180" width="130" onerror=this.src="uploads/default.jpg"></a></p>
					<p><small><a href="viewchamp.php?id=<?= $row['id']?>"><?= $row['name']?></a></small></p>
				</h2>
			<?php endwhile ?>
			<?php
				$query = 'SELECT COUNT(*) AS total FROM champions WHERE name LIKE "%' . $_POST['search'] . '%"';
				$statement = $db->prepare($query);
				$statement->execute();
				$row = $statement->fetch();
				if ($row['total'] == 0): 
			?>
				<h2>No champion found</h2>
			<?php endif ?>
		<?php endif ?>
		</div>
	</div>
</body>
</html>