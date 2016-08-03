<?php

	require_once 'connMongoDB.php';
	require_once 'entities.php';
	include_once 'functions.php';
	if ( is_session_started() === FALSE ) session_start();

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
					addLogLine($params['gameID'].' cambia a '.$params['choice'], null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
					if (!strcmp($params['choice'], 'notPlayed')) {
						$user->removeFinishedGame($params['gameID']);
						$user->removePlayingGame($params['gameID']);
						$choice = 'notPlayed';
					}
					if (!strcmp($params['choice'], 'playing')) {
						$user->addPlayingGame($params['gameID']);
						$choice = 'playing';
					}
					if (!strcmp($params['choice'], 'finished')) {
						$user->addFinishedGame($params['gameID']);
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