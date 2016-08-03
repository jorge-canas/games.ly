<?php

require_once 'connMongoDB.php';
require_once 'entities.php';
include_once 'functions.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
	$params = json_decode(file_get_contents('php://input'),true);
	$error = false;
	$message = "";
	$response = array();

	//$limit = (isset($params['limit'])) ? $params['limit'] : 0 ;
	if (!isset($params['searchString'])) {
		$error = true;
		$message = "No se ha buscado ninguna noticia.";
	}

	if (!isset($params['field'])) {
		$field = 'title';
	}else{
		$field = $params['field'];
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

			$noticeList = $notice->searchNotices($params['searchString'], $field);
			if ( is_session_started() === FALSE ) session_start();
			addLogLine('Se ha buscado la noticia '.$params['searchString'], null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
			foreach ($noticeList as $notice) {
				array_push($response, $notice);
			}
		}else{
			$error = true;
			$message = "Hay un problema con la base de datos, pruebe de nuevo en unos minutos";
		}
	}

	$result = array(
		'error' => $error,
		'message' => $message,
		'response' => $response
	);

	$result = json_encode($result);
	echo $result;
}