<?php

	require_once 'connMongoDB.php';
	require_once 'entities.php';

	//include_once('functions.php');
	//if ( is_session_started() === FALSE ) session_start();
	//addLogLine('Accede a coger elección', null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');

	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
		$params = json_decode(file_get_contents('php://input'),true);
		$error = false;
		$message = "";
		$choice = 'notPlayed';

		if (empty($params['gameID'])){
				$error = true;
				$message = "El identificador de juego está vacio.";
		}

		if (!$error) {
			$conn = new MongoConn();
			$mongo = $conn->connect();
			if ($mongo) {
				//seleccionar base datos
				$db = $mongo->selectDB("gamesly");

				//selección de la colección
				$collection = $db->selectCollection("User");

				$user = new User($collection);

				$iniciado = $user->initUser($_SESSION['username']);

				if ($iniciado) {
					$playing = $user->getPlayingGames();
					$finished = $user->getFinishedGames();
					if (in_array($params['gameID'], $playing)) {
						$choice = 'playing';
					}

					if (in_array($params['gameID'], $finished)) {
						$choice = 'finished';
					}
				}else{
					$error = true;
					$message = "Hay un problema al coger la información.";
				}
			}
		}
		$result = array(
			'error' => $error,
			'message' => $message,
			'choice' => $choice
		);

		$result = json_encode($result);
		echo $result;
	}