<?php
	//require_once __DIR__.'/vendor/autoload.php';
	require_once '../vendor/autoload.php';
	require_once 'connMongoDB.php';
	require_once 'entities.php';
	include_once 'functions.php';
	use Goutte\Client;

	if ( is_session_started() === FALSE ) session_start();
	addLogLine('Intenta agregar un juego', null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');

	if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
		$params = json_decode(file_get_contents('php://input'),true);
		$error = false;
		$message = "";

		if (empty($params['gameUrl'])){
				$error = true;
				$message = "La dirección está vacia.";
		}

		if (!$error) {
			$conn = new MongoConn();
			$mongo = $conn->connect();
			if ($mongo) {
				//seleccionar base datos
				$db = $mongo->selectDB("gamesly");

				//selección de la colección
				$collection = $db->selectCollection("Game");

				$newGame = new Game($collection);
				$client = new Client();

				$gameIDParts = explode("/", $params['gameUrl']);

				if (isset($gameIDParts[2]) && strcmp($gameIDParts[2], "www.meristation.com")==0) {
					//Identificador
					$gameID = $gameIDParts[4]."-".$gameIDParts[6];

					if (!$newGame->duplicateGame($gameID)) {
						$crawler = $client->request('GET', $params['gameUrl']);
						addLogLine('Scrap '.$params['gameUrl'], null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
						//$crawler = $client->request('GET', 'games.ly/php/pruebas/metal.html');
						//var_dump($crawler);

						//Nombre del juego
						if ($crawler->filter('h1.game-title')->count() > 0) {
							$gameName = $crawler->filter('h1.game-title')->text();
						}else{
							$gameName = "Título vacío";
						}					
						
						//Descripción
						if ($crawler->filter('div.pane-game-tech-head > div > table > tbody > tr > td > p')->count() > 0) {
							$description = $crawler->filter('div.pane-game-tech-head > div > table > tbody > tr > td > p')->text();
						}else{
							$description = "Sin descripción";
						}

						//Imágen
						if ($crawler->filter('img.imagecache-gamebox')->count() > 0) {
							$imageUrl = $crawler->filter('img.imagecache-gamebox')->attr('src');
						}else{
							$imageUrl = null;
						}

						//Desarrolladora
						if ($crawler->filter('.field-type-nodereference > div > div > a')->count() > 0) {
							$developer = $crawler->filter('.field-type-nodereference > div > div > a')->html();
						}else{
							$developer = "Sin desarrolladora";
						}					

						//Géneros
						$genres = array();
						if ($crawler->filter('.field-field-gender > div > div > a')->count() > 0) {
							$crawler->filter('.field-field-gender > div > div > a')->each(function($genre){
								array_push($GLOBALS['genres'], $genre->text());
							});						
						}

						//Subgéneros
						$subgenres = array();
						if ($crawler->filter('.field-field-subgender > div > div')->count() > 0) {
							$sgDirty = $crawler->filter('.field-field-subgender > div > div')->text();
							$sgDirty = str_replace("Subgéneros:", "", $sgDirty);
							$sg = explode(",", $sgDirty);
							$sg = array_map('trim', $sg);
							foreach ($sg as $subgenre) {
								array_push($GLOBALS['subgenres'], trim($subgenre));
							}
						}					

						//Lanzamiento
						if ($crawler->filter('div.field-type-datestamp > div > div > span')->count() > 0) {
							$release = $crawler->filter('div.field-type-datestamp > div > div > span')->html();
						}else{
							$release = "";
						}

						//Voces
						$voices = array();
						if ($crawler->filter('.field-field-voices-language > div > div')->count() > 0) {
							$voicesDirty = $crawler->filter('.field-field-voices-language > div > div')->text();
							$voicesDirty = str_replace("Voces:", "", $voicesDirty);
							
							$voicesClean = explode(",", $voicesDirty);
							//eliminar espacios en blanco y otros elementos
							$voicesClean = array_map('trim', $voicesClean);
							foreach ($voicesClean as $voice) {
								array_push($GLOBALS['voices'], trim($voice));
							}
						}

						//Textos
						$texts = array();
						if ($crawler->filter('.field-field-text-language > div > div')->count() > 0) {
							$textsDirty = $crawler->filter('.field-field-text-language > div > div')->text();
							$textsDirty = str_replace("Texto:", "", $textsDirty);
							$textsClean = explode(",", $textsDirty);
							$textsClean = array_map('trim', $textsClean);
							foreach ($textsClean as $text) {
								array_push($GLOBALS['texts'], trim($text));
							}	
						}

						//Plataformas
						$platforms = array();
						if ($crawler->filter('.field-field-platform > div > div > a ')->count() > 0) {
							array_push($platforms, $crawler->filter('.field-field-platform > div > div > a ')->text());
							if ($crawler->filter('.pane-game-platforms-list > div > div > div > a')->count() > 0) {
								$crawler->filter('.pane-game-platforms-list > div > div > div > a')->each(function($platform){
									array_push($GLOBALS['platforms'], $platform->text());
								});
							}
						}
						

						if ($imageUrl) {
							$gameIMG = $gameID.".png";
						}else{
							$gameIMG = "emptyImage.png";
						}

						$game = array(
							"gameID" => $gameID,
							"gameIMG" => $gameIMG,
							"gameName" => $gameName,
							"description" => $description,
							"developer" => $developer,
							"genres" => $genres,
							"subgenres" => $subgenres,
							"release" => $release,
							"platforms" => $platforms,
							"voices" => $voices,
							"texts" => $texts
							);
						
						$newGame->setGame($game);
						$newGame->save();

						//Coger la foto
						if ($imageUrl) {
							$content = file_get_contents($imageUrl);
							//Almacenarla en el sistema
							$fp = fopen("../img/games/$gameIMG", "w");
							fwrite($fp, $content);
							fclose($fp);
						}

						$message ="El juego se ha añadido con éxito.";
						addLogLine('Se ha añadido el juego '.$gameName, null!==$_SESSION['username'] ? $_SESSION['username'] : 'Desconocido');
					}else{
						$error = true;
						$message = "El juego ya está en nuestra base de datos.";
					}
				}else{
					$error = true;
					$message = "La direccion proporcionada no tiene el formato adecuado. Compruebe que la direccion esta completa.";
				}
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