<?php

	require_once 'connMongoDB.php';
	require_once 'entities.php';
	include_once('functions.php');

	$error = false;
	$message = "No tienes permisos para acceder a esta acción.";
	$response = false;
	
	//Comprobar que quien hace la petición es administrador
	if ( is_session_started() === FALSE ) session_start();
	addLogLine('Accede a borrar juego', null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && isset($_SESSION['adminId'])){
		$params = json_decode(file_get_contents('php://input'),true);

		if (empty($params)){
				$error = true;
				$message = "El identificador del juego está vacío.";
		}

		if (!$error) {
			$conn = new MongoConn();
			$mongo = $conn->connect();
			if ($mongo) {
				//seleccionar base datos
				$db = $mongo->selectDB("gamesly");

				//selección de la colección
				$collection = $db->selectCollection("Game");

				$game = new game($collection);
				$response = $game->deleteGame($params);
				if ($response) {
					addLogLine('Elimina el juego '.$params, null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
					$message = "El juego se ha eliminado con éxito.";
				}
			}
		}
	}

	$result = array(
		'error' => $error,
		'message' => $message,
		'response' => $response
	);

	$result = json_encode($result);
	echo $result;