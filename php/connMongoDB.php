<?php
	include_once('functions.php');

	class MongoConn{
		private $dbUsername;
		private $dbPass;
		private $host;
		private $dbname;

		function __construct(){
			if ( is_session_started() === FALSE ) session_start();
			if(isset($_SESSION['adminId']) && $_SESSION['adminId']){
				//$this->dbUsername = "gameslyAdmin";
				$this->dbUsername = "gameslyUser";
				$this->dbPass = "pass";
			}else{
				$this->dbUsername = "gameslyUser";
				$this->dbPass = "pass";
			}
			$this->host = "localhost";
			$this->dbname = "gamesly";
		}

		public function connect(){
			$dbUsername = $this->dbUsername;
			$dbPass = $this->dbPass;
			$host = $this->host;
			$dbname = $this->dbname;
			try{
				return new MongoClient("mongodb://$dbUsername:$dbPass@$host", array("db"=>$dbname));
				//return new MongoClient();
				//conectado
				//echo "conectado";
			}catch (MongoException $e){
				return null;
				/*
				$connError = array(
						'error' => true,
						'message' => "Hay un problema con la conexión y no se ha podido realizar correctamente"
					);
				
				$connError = json_encode($connError);
				return $connError;
				*/
			}
		}
	}
