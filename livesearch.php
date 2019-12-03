<?php
  require('db_connect.php');

  $ajax_data = [
    "success" => false,
    "message" => 'Missing GET parameter.',
    "name"  => []
  ];

	$name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
	$query = 'SELECT id, name FROM champions WHERE name LIKE "%' . $name . '%" ORDER BY name';
	$statement = $db->prepare($query);
	$statement->execute();
		
	$champ = $statement->fetchAll(PDO::FETCH_ASSOC);
	$number_of_champs = count($champ);

	if ($number_of_champs === 0) {
	  $ajax_data['message'] = "No champion found.";
	} else {
		$ajax_data['success'] = true;
		$ajax_data['message'] = "Found {$number_of_champs} champs.";
		$ajax_data['name'] = $champ;
	}
	

  header('Content-Type: application/json');
  echo json_encode($ajax_data);
?>