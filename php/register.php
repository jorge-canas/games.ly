<?php

	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
		$params = json_decode(file_get_contents('php://input'),true);
		//var_dump($params);
		//var_dump($_POST);
		$error = false;
		$message = "";

		//validación
		if (empty($params['username']) || 
			empty($params['password1']) ||
			empty($params['password2']) ||
			empty($params['email1']) ||
			empty($params['email2'])
			) {
				$error = true;
				$message = "No se ha cumplido con los requisitos del formulario, compruebeló y rellene los campos vacíos"				;
		}
		if (!$error) {
			$insert = array(
				"username" => $params['username'],
				"password" => password_hash($params['password1'], PASSWORD_BCRYPT),
				"email" => $params['email1'],
				"role" => 0);

			require_once("connMongoDB.php");
			try{
				//seleccionar base datos
				$db = $mongo->gamesly;

				//selección de la colección
				$collection = $db->selectCollection("users");

				$username = $params['username'];

				//Comprobar que el usuario no existe
				$result = $collection->findOne(array('username' => $username));

				if ($result['_id']) {
					$error = true;
					$message = "El usuario ya existe, por favor elija otro";
				}else{
					//insertar nuevo elemento
					$result = $collection->insert($insert);
					$mongo->close();
					if ($result["ok"]) {
						$message = "El usuario se ha registrado con éxito.";
					}else{
						$message = "Ha habido un problema y el usuario no se ha podido crear";
					}
				}
			}catch(MongoException $e){
				$result = array(
					'error' => true,
					'message' => $e->getMessage()
				);
				$result = json_encode($result);
				echo $result;
			}
		}
		
		$result = array(
					'error' => $error,
					'message' => $message
				);
		$result = json_encode($result);
		echo $result;
	}
