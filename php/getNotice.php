<?php

	require_once 'connMongoDB.php';
	require_once 'entities.php';

	include_once('functions.php');
	if ( is_session_started() === FALSE ) session_start();
	addLogLine('Accede a coger noticia', null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');

	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
		$params = json_decode(file_get_contents('php://input'),true);
		$error = false;
		$message = "";
		$response = array();

		if (empty($params['noticeID'])){
				$error = true;
				$message = "La dirección está vacia";
		}

		if (!$error) {
			$conn = new MongoConn();
			$mongo = $conn->connect();
			if ($mongo) {
				//DB selection
				$db = $mongo->selectDB("gamesly");

				//Collection selection
				$collection = $db->selectCollection("Notice");

				$notice = new Notice($collection);
				$response = $notice->getNotice($params['noticeID']);
				addLogLine('Solicita la noticia '.$params['noticeID'], null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
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