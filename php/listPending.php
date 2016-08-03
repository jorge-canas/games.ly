<?php

	require_once('functions.php');

	$error = false;
	$message = "";
	

	if(isset($_SESSION['userId']){
		$id = $_SESSION['userId'];
	}else if (isset($_SESSION['adminId'])) {
		$id = $_SESSION['adminId'];
	}else{
		$error = true;
		$message = "Hay un problema o no estás identificado";
	}

	if (!$error) {
		require_once('connMongoDB.php');
		try{
			$db = $mongo->gamesly;

			//selección de la colección
			$userCollection = $db->selectCollection("users");

			$id = new MongoId($id);

			//Comprobar que el usuario no existe
			$result = $userCollection->findOne(array('_id' => $id), array('pendingGames'));
			var_dump($result);

			if ($result['_id']) {
				$gamesColection = $db->selectCollection("games");

				$result = $gamesColection->find($result['pendingGames']);

				foreach ($cursor as $key => $value) {
			        $etiquetas = $value['etiquetas'];
			        foreach ($etiquetas as $etiquetasKey => $etiquetasValue) {
			            if (strcmp($etiquetasValue, "Ciencia") == 0) {
			                echo "<strong>Titular</strong> ". $value['titular']."<br />";
			                echo "<strong>Frases descatacas:</strong><br />";
			                $frasesDestacadas = $value['frases_destacadas'];
			                foreach ($frasesDestacadas as $subKey => $subValue) {
			                    echo $subValue."<br />";
			                }
			                echo "<br />";
			            }
			        }
			        
			    }
			}else{
				$message = "Actualmente no tiene ningún juego pendiente, agregue alguno a su lista";
			}

			$mongo->close();

		}catch(MongoException $e){
			$mongo->close();
			$error = true;
			$message = "Hay un error con la base de datos, pruebe de nuevo en unos minutos";
		}
	}
		

	$result = json_encode($result);
	echo $result;