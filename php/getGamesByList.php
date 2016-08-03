<?php

	require_once 'connMongoDB.php';
	require_once 'entities.php';

	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
		$params = json_decode(file_get_contents('php://input'),true);
		$error = false;
		$message = "";
		$games = array();

		if (empty($params['choice'])){
				$error = true;
				$message = "Faltan parámetros.";
		}

		if (!$error) {
			$conn = new MongoConn();
			$mongo = $conn->connect();
			if ($mongo) {
				//seleccionar base datos
				$db = $mongo->selectDB("gamesly");

				//selección de la colección
				$userCollection = $db->selectCollection("User");

				$user = new User($userCollection);

				$iniciado = $user->initUser($_SESSION['username']);

				if ($iniciado) {
					if (!strcmp($params['choice'], 'playing')) {
						$gameList = $user->getPlayingGames();
					}
					if (!strcmp($params['choice'], 'finished')) {
						$gameList = $user->getFinishedGames();
					}
				}else{
					$error = true;
					$message = "Hay un problema al coger la información.";
				}
				if (!$error) {
					$gameCollection = $db->selectCollection("Game");
					$gameObj = new Game($gameCollection);
					foreach ($gameList as $game) {
						//var_dump($gameObj->getGame($game));
						array_push($games, $gameObj->getGame($game));
					}
				}
			}
		}
		$result = array(
			'error' => $error,
			'message' => $message,
			'response' => $games
		);

		$result = json_encode($result);
		echo $result;
	}