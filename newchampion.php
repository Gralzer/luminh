<?php
	require 'authenticate.php';
	require('db_connect.php');
	
	function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
       $current_folder = dirname(__FILE__);
       
       $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
       
       return join(DIRECTORY_SEPARATOR, $path_segments);
    }
	
	function file_is_an_image($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        $actual_mime_type        = getimagesize($temporary_path)['mime'];
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        
        return $file_extension_is_valid && $mime_type_is_valid;
    }
	
	if (isset($_POST['submit']) && !empty($_POST['name']) && !empty($_POST['type']) && !empty($_POST['style']) && !empty($_POST['difficulty']) && !empty($_POST['strength'])) {
        
		//  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $style = filter_input(INPUT_POST, 'style', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$difficulty = filter_input(INPUT_POST, 'difficulty', FILTER_SANITIZE_NUMBER_INT);
		$strength = filter_input(INPUT_POST, 'strength', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
        //  Build the parameterized SQL query and bind to the above sanitized values.
		$query = "INSERT INTO champions (name, type, style, difficulty, strength) VALUES (?, ?, ?, ?, ?)";
        $statement = $db->prepare($query);
		
        //  Bind values to the parameters
		$statement->bindValue(1, $name);
        $statement->bindValue(2, $type);
		$statement->bindValue(3, $style);
		$statement->bindValue(4, $difficulty);
		$statement->bindValue(5, $strength);
		
		if($statement->execute()){
			echo "Success";
			
			$image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
			if ($image_upload_detected) { 
				$image_filename        = $_FILES['image']['name'];
				$temporary_image_path  = $_FILES['image']['tmp_name'];
				$new_image_path        = file_upload_path($image_filename);
				if (file_is_an_image($temporary_image_path, $new_image_path)) {
					move_uploaded_file($temporary_image_path, $new_image_path);
					
					$query = "INSERT INTO images (file, champ) VALUES (?, ?)";
					$statement = $db->prepare($query);
					
					$statement->bindValue(1, $image_filename);
					$statement->bindValue(2, $name);
					
					if($statement->execute()){
						echo "Upload success";
					}
				}
			}
			
			header('Location: champions.php');
			exit;
		} else {
			echo "Error releasing new champion";
		}
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
			<h1>Release a new champion</h1>
		</div>
		
		<div>
			<form action="newchampion.php" method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>New champion info</legend>
					<p>Name: <input name="name" id="name"/></p>
					<p>Type: <input name="type" id="type"/></p>
					<p>Style: <input name="style" id="style"/></p>
					<p>Difficulty: <input name="difficulty" id="difficulty"/></p>
					<p>Strength: 
						<select id="strength" name="strength">
							<option selected disabled hidden></option>
							<?php
								$query1 = 'SELECT * FROM categories';
								$statement1 = $db->prepare($query1);
								$statement1->execute();
								while ($row1 = $statement1->fetch()): 
							?>
								<option><?= $row1['category']?></option>
							<?php endwhile?>
						</select>
					</p>
					<p>Upload Images: <input type="file" name="image" id="image"></p>
					<p><input type="submit" name="submit" value="Release"/></p>
				</fieldset>
			</form>
		</div>
		
		<div>
		
		</div>
	</div>
</body>
</html>