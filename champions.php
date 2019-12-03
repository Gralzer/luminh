<?php
	require('db_connect.php');

    $sort = 0;
	
	$query = 'SELECT champions.id, champions.name, images.file FROM champions LEFT JOIN images ON champions.name = images.champ ORDER BY champions.strength';
	
	if (isset($_GET['sort'])) {
		$sort = $_GET['sort'];
		
		switch ($_GET['sort']) {
			case 1:
				$query = 'SELECT champions.id, champions.name, images.file FROM champions LEFT JOIN images ON champions.name = images.champ ORDER BY name';
				break;
			case 2:
				$query = 'SELECT champions.id, champions.name, images.file FROM champions LEFT JOIN images ON champions.name = images.champ ORDER BY id';
				break;
			case 3:
				$query = 'SELECT champions.id, champions.name, images.file FROM champions LEFT JOIN images ON champions.name = images.champ ORDER BY difficulty';
				break;
			default:
				$query = 'SELECT champions.id, champions.name, images.file FROM champions LEFT JOIN images ON champions.name = images.champ ORDER BY strength';
		}
	}
	
    $statement = $db->prepare($query);
	//exec sql query
    $statement->execute(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Champions</title>
<style>
	.dropbtn {
	  background-color: #4CAF50;
	  color: white;
	  padding: 5px;
	  font-size: 16px;
	  border: none;
	}

	.dropdown {
	  position: relative;
	  display: inline-block;
	}

	.dropdown-content {
	  display: none;
	  position: absolute;
	  background-color: #f1f1f1;
	  min-width: 120px;
	  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	  z-index: 1;
	}

	.dropdown-content a {
	  color: black;
	  padding: 8px 5px;
	  text-decoration: none;
	  display: block;
	}

	.dropdown-content a:hover {background-color: #ddd;}

	.dropdown:hover .dropdown-content {display: block;}

	.dropdown:hover .dropbtn {background-color: #3e8e41;}
</style>
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
			<h1>League of Legends Champions</h1>
		</div>
		
		<div>
			<a href="newchampion.php"><input type="button" value="add" name="add"/></a>
			<select onchange="location = this.value;">
				<option <?=$sort==0?'selected="selected"':'';?> disabled hidden>Sort by</option>
				<option <?=$sort==1?'selected="selected"':'';?> value = "champions.php?sort=1">Name</option>
				<option <?=$sort==2?'selected="selected"':'';?> value = "champions.php?sort=2">Released</option>
				<option <?=$sort==3?'selected="selected"':'';?> value = "champions.php?sort=3">Difficulty</option>
			</select>
		</div>
		
		<div class="dropdown">
		  <button class="dropbtn">Category</button>
		  <div class="dropdown-content">
			<?php
				$query1 = 'SELECT * FROM categories';
				$statement1 = $db->prepare($query1);
				$statement1->execute();
				while ($row1 = $statement1->fetch()): 
			?>
				<a href="#" onclick="category('<?= $row1['category']?>')"><?= $row1['category']?></a>
			<?php endwhile?>
		  </div>
		</div>
		
		<div>
			<input name="filter" id="filter" placeholder="Filter by Champion Name" onkeyup="filter()"/>
		</div>
		
		<div class="result" id="result">
			<?php while ($row = $statement->fetch()):?>
				<h2>
					<p><a href="viewchamp.php?id=<?= $row['id']?>"><img src="uploads/<?= $row['file']?>" height="180" width="130" onerror=this.src="uploads/default.jpg"></a></p>
					<p><small><a href="viewchamp.php?id=<?= $row['id']?>"><?= $row['name']?></a> <a href="editchamp.php?id=<?= $row['id']?>">edit</a></small></p>
				</h2>
			<?php endwhile ?>
		</div>		
	</div>
	<script src="script.js"></script>
</body>
</html>