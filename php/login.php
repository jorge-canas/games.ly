<?php

	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
		$params = json_decode(file_get_contents('php://input'),true);
		$error = false;
		$message = "";
		
		require_once("connMongoDB.php");
		try{
			//seleccionar base datos
			$db = $mongo->gamesly;

			//selección de la colección
			$collection = $db->selectCollection("users");

			//recojo los valores pasados por POST
			$username = $params['username'];
			$password = $params['password'];
			
			//Comprobar que el usuario no existe
			$result = $collection->findOne(array('username' => $username), array('_id', 'username', 'password', 'role'));
			$mongo->close();
			if ($result['_id']) {
				$colPass = $result['password'];
				if (password_verify($password, $colPass)) {
					include_once('functions.php');
					if ( is_session_started() === FALSE ) session_start();
					if ($result['role'] === 10) {
						$_SESSION['adminId'] = $result['_id'];
					}else{
						$_SESSION['userId'] = $result['_id'];
					}
					$_SESSION['username'] = $result['username'];
				}else{
					$error = true;
				}
			}else{
				$error = true;
			}

			if ($error) {
				$message = "El usuario o la contraseña es incorrecto";
			}

			$result = array(
				'error' => $error,
				'message' => $message
				);
			$result = json_encode($result);
			echo $result;
		}catch(MongoException $e){
			$mongo->close();
			$result = array(
				'error' => true,
				'message' => $e->getMessage()
				);
			$result = json_encode($result);
			echo $result;
		}
	}    
