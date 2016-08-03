<?php

require_once 'connMongoDB.php';
require_once 'entities.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
	$params = json_decode(file_get_contents('php://input'),true);

	//$limit = (isset($params['limit'])) ? $params['limit'] : 0 ;
	if (isset($params['limit'])) {
		$limit = $params['limit'];
	}else{
		$limit = 0;
	}

	$error = false;
	$message = "";
	$response = array();

	$conn = new MongoConn();
	$mongo = $conn->connect();
	if ($mongo) {
		//DB selection
		$db = $mongo->selectDB("gamesly");

		//Collection selection
		$collection = $db->selectCollection("Game");

		$game = new Game($collection);

		$gameList = $game->listGames($limit);
		foreach ($gameList as $game) {
			array_push($response, $game);
		}
		
	}else{
		$error = true;
		$message = "Hay un problema con la base de datos, pruebe de nuevo en unos minutos";
	}

	$result = array(
		'error' => $error,
		'message' => $message,
		'response' => $response
	);

	$result = json_encode($result);
	echo $result;
}