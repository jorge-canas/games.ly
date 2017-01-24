<?php

	require_once("connMongoDB.php");
	require_once("entities.php");
	include_once 'functions.php';
	if ( is_session_started() === FALSE ) session_start();
	addLogLine('Accede a perfil de usuario', null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
	
	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
		$params = json_decode(file_get_contents('php://input'),true);
		$error = false;
		$message = "";

		//var_dump($params);

		//validation
		if (empty($params['choice'])) {
			$error = true;
			$message = "No se ha elegido una opción.";
		}

		if (!$error) {
			//Check change password choice
			if (strcmp($params['choice'], 'password')==0){
				if(empty($params['oldPassword']) || 
				empty($params['newPassword1'])
				){
					$error = true;
					$message = "Faltan parámetros";
				}else if (strcmp($params['newPassword1'], $params['newPassword2'])!=0) {
					$error = true;
					$message = "Los campos de la nueva contraseña no concuerdan.";
				}
			}

			//Check change email choice
			if (strcmp($params['choice'], 'email')==0 ){
				if(empty($params['oldEmail']) || 
				empty($params['newEmail1'])
				){
				echo "email";
				$error = true;
				$message = "Faltan parámetros";
				}else if (strcmp($params['newEmail1'], $params['newEmail2'])!=0) {
					$error = true;
					$message = "Los campos de la nueva contraseña no concuerdan.";
				}
			}

			if (!$error) {
				$conn = new MongoConn();
				$mongo = $conn->connect();
				if ($mongo) {
					//DB selection
					$db = $mongo->selectDB("gamesly");

					//Collection selection
					$collection = $db->selectCollection("User");

					$user = new User($collection);
					include_once('functions.php');
					if ( is_session_started() === FALSE ) session_start();
					$iniciado = $user->initUser($_SESSION['username']);

					if ($iniciado) {
						if (strcmp($params['choice'], 'password')==0){
							if(password_verify($params['oldPassword'], $user->getPassword())) {
								$user->setPassword($params['newPassword1']);
								$message = "La contraseña se ha cambiado con éxito.";
								addLogLine('Ha cambiado su contraseña con éxito', null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
							}else{
								$error = true;
								$message = "La contraseña antigua es incorrecta.";
								addLogLine('Ha intentado cambiar su contraseña erróneamente', null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
							}
						}

						if (strcmp($params['choice'], 'email')==0){
							if(strcmp($params['oldEmail'], $user->getEmail())==0) {
								$user->setEmail($params['newEmail1']);
								$message = "El correo se ha cambiado con éxito.";
								addLogLine('Ha cambiado su correo con éxito', null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
							}else{
								$error = true;
								$message = "El correo antiguo es incorrecto.";
								addLogLine('Ha intentado cambiar su correo erróneamente', null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
							}
						}

					}else{
						$error = true;
						$message = "El usuario o la contraseña son incorrectas";
					}					
				}else{
					$error = true;
					$message = "Hay un problema con la base de datos";
				}
				$mongo->close();
			}
		}

		$result = array(
			'error' => $error,
			'message' => $message
		);

		$result = json_encode($result);
		echo $result;
	}
