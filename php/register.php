<?php

	require_once("connMongoDB.php");
	require_once("entities.php");
	include_once('functions.php');
	addLogLine('Accede a registro', 'Desconocido');
	
	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
		$params = json_decode(file_get_contents('php://input'),true);
		//var_dump($params);
		//var_dump($_POST);
		$error = false;
		$message = "";
		/*
		$params = array(
			"username" => "pepapig",
			"password1" => "pass",
			"password2" => "pass",
			"email1" => "prueba@prueba.es",
			"email2" => "prueba@prueba.es"
			);
		*/
		//validación
		if (empty($params['username']) || 
			empty($params['password1']) ||
			empty($params['password2']) ||
			empty($params['email1']) ||
			empty($params['email2'])
			) {
				$error = true;
				$message = "No se ha cumplido con los requisitos del formulario, compruebeló y rellene los campos vacíos";
		}

		if (strcmp($params['password1'], $params['password2'])!=0) {
			$error = true;
			$message = "La contraseña no coincide";
		}

		if (strcmp($params['email1'], $params['email2'])!=0) {
			$error = true;
			$message = "El correo no coincide";
		}

		if (!$error) {
			$newUser = array(
				"username" => $params['username'],
				"password" => $params['password1'],
				"email" => $params['email1']
			);

			$conn = new MongoConn();
			$mongo = $conn->connect();
			if ($mongo) {
				//seleccionar base datos
				$db = $mongo->selectDB("gamesly");

				//selección de la colección
				$collection = $db->selectCollection("User");

				$user = new User($collection);

				if(!$user->duplicateUsername($params['username'])){
					if(!$user->duplicateEmail($params['email1'])){
						$user->newUser($newUser);
						$message = "El usuario se ha insertado correctamente";
						addLogLine($params['username'].' se ha registrado');
					}else{
						$error = true;
						$message = "El correo ya existe, por favor elija otro";
						addLogLine('El correo '.$params['email1'].' ya existe ');
					}
				}else{
					$error = true;
					$message = "El usuario ya existe, por favor elija otro";
					addLogLine('El usuario '.$params['username'].' ya existe ');
				}
			}else{
				$error = true;
				$message = "Hay un problema con la base de datos";
			}
		}
		$mongo->close();
		$result = array(
			'error' => $error,
			'message' => $message
		);

		$result = json_encode($result);
		echo $result;
	}
