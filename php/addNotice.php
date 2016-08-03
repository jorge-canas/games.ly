<?php
	require_once 'connMongoDB.php';
	require_once 'entities.php';

	include_once('functions.php');
	if ( is_session_started() === FALSE ) session_start();
	addLogLine('Accede añadir noticias', null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');

	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
		$params = json_decode(file_get_contents('php://input'),true);
		$error = false;
		$imageUrl = null;
		$source = '';
		$message = "";

		if (empty($params['title']) ||
			empty($params['noticeBody'])
			){
				$error = true;
				$message = "Faltan campos por rellenar.";
		}

		if (!$error) {
			$conn = new MongoConn();
			$mongo = $conn->connect();
			if ($mongo) {
				//DB selection
				$db = $mongo->selectDB("gamesly");

				//Collection selection
				$collection = $db->selectCollection("Notice");

				$newNotice = new Notice($collection);

				if (empty($params['noticeIMG']) || strcmp($params['noticeIMG'], 'Imagen') == 0) {
					$noticeIMG = 'emptyImage.png';
				}else{
					$noticeIMG = hash('md5', $params['noticeIMG']);
					$noticeIMG = $noticeIMG.'.png';
					$imageUrl = $params['noticeIMG'];
				}

				if (empty($params['source']) || strcmp($params['source'], 'Fuente') == 0) {
					//$source = 'Sin fuente';
				}else{
					$source = $params['source'];
				}				
				addLogLine('Añade noticia '.$params['title'], null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
				$notice = array(
					"title" => $params['title'],
					"noticeIMG" => $noticeIMG,
					"noticeBody" => $params['noticeBody'],
					"source" => $source
					);
				
				$newNotice->initNotice($notice);
				$newNotice->save();

				//Get image
				if ($imageUrl) {
					$content = file_get_contents($imageUrl);
					//Store it in the system
					$fp = fopen("../img/notices/$noticeIMG", "w");
					fwrite($fp, $content);
					fclose($fp);
				}
				
				$message ="La noticia se ha añadido con éxito.";

			}else{
				$error = true;
				$message = "Hay un problema con la base de datos, pruebe de nuevo en unos minutos.";
			}
		}
		$result = array(
			'error' => $error,
			'message' => $message
		);

		$result = json_encode($result);
		echo $result;
	}