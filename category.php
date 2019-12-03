<?php
  require('db_connect.php');

  $category = [
    "success" => false,
    "message" => 'Missing GET parameter.',
    "cate"  => []
  ];
		
	$cate = filter_input(INPUT_GET, 'cate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
	$query = 'SELECT id, name FROM champions WHERE strength = "' . $cate . '" ORDER BY name';
	$statement = $db->prepare($query);
	$statement->execute();
		
	$champ = $statement->fetchAll(PDO::FETCH_ASSOC);
	$number_of_champs = count($champ);

	if ($number_of_champs === 0) {
	  $category['message'] = "No champion found.";
	} else {
		$category['success'] = true;
		$category['message'] = "Found {$number_of_champs} champs.";
		$category['cate'] = $champ;
	}
	

  // Return the data in JSON format.
  header('Content-Type: application/json');
  echo json_encode($category);
?>