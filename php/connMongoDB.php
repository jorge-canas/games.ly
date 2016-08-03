<?php
	include_once('functions.php');
	if ( is_session_started() === FALSE ) session_start();
	if(isset($_SESSION['adminId']) && $_SESSION['adminId']){
		$username = "gameslyAdmin";
		$pass = "pass";
	}else{
		$username = "gameslyUser";
		$pass = "pass";
	}

	$host = "localhost";
	
	$dbname = "gamesly";
	try{
		$mongo = new MongoClient("mongodb://$username:$pass@$host", array("db"=>$dbname));
		//conectado
		//echo "conectado";
	}catch (MongoException $e){
		echo "mal";
		$connError = array(
				'error' => true,
				'message' => "Hay un problema con la conexión y no se ha podido realizar correctamente"
			);
		$connError = json_encode($connError);
		echo $connError;
		die();
	}
