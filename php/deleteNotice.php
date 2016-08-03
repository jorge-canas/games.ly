<?php

	require_once 'connMongoDB.php';
	require_once 'entities.php';
	include_once 'functions.php';

	$error = false;
	$message = "No tienes permisos para acceder a esta acción.";
	$response = false;
	//Comprobar que quien hace la petición es administrador
	if ( is_session_started() === FALSE ) session_start();
	addLogLine('Accede a borrar noticia', null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && isset($_SESSION['adminId'])){
		$params = json_decode(file_get_contents('php://input'),true);
		echo $params;
		if (empty($params)){
				$error = true;
				$message = "El identificador de la noticia está vacío.";
		}

		if (!$error) {
			$conn = new MongoConn();
			$mongo = $conn->connect();
			if ($mongo) {
				//seleccionar base datos
				$db = $mongo->selectDB("gamesly");

				//selección de la colección
				$collection = $db->selectCollection("Notice");

				$notice = new Notice($collection);
				$response = $notice->deleteNotice($params);
				if ($response) {
					addLogLine('Elimina la noticia '.$params, null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
					$message = "La noticia se ha eliminado con éxito.";
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