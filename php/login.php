<?php
	
	include_once('functions.php');
	if ( is_session_started() === FALSE ) session_start();
	addLogLine('Accede a login', 'Desconocido');

	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
		$params = json_decode(file_get_contents('php://input'),true);
		$error = false;
		$message = "";

		if (empty($params['username']) || 
			empty($params['password'])
			){
				$error = true;
				$message = "Existen campos vacios";
		}

		if (!$error) {
			require_once("connMongoDB.php");
			require_once("entities.php");

			$conn = new MongoConn();
			$mongo = $conn->connect();
			if ($mongo) {
				//DB selection
		$db = $mongo->selectDB("gamesly");

		//Collection selection
				$collection = $db->selectCollection("User");

				$user = new User($collection);

				$iniciado = $user->initUser($params['username']);

				if ($iniciado) {
					if (password_verify($params['password'], $user->getPassword())) {
						//User verified
						if ($user->getRole() === 10) {
							$_SESSION['adminId'] = $user->getID();
						}else{
							$_SESSION['userId'] = $user->getID();
						}
						$_SESSION['username'] = $user->getUsername();
						addLogLine('Accede a la página', $_SESSION['username']);
					}else{
						addLogLine('Password incorrecto');
						$error = true;
						$message = "El usuario o la contraseña son incorrectas";
					}
				}else{
					addLogLine('El usuario no existe');
					$error = true;
					$message = "El usuario o la contraseña son incorrectas";
				}
			}else{
				$error = true;
				$message = "Hay un problema con la base de datos";
			}
		}
		
		$result = array(
			'error' => $error,
			'message' => $message
		);

		$result = json_encode($result);
		echo $result;
	}
