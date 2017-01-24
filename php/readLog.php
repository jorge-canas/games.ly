<?php

try{
    $logLines = array();
    $file = "../log/log.csv";
    $error = false;
    $message = "";
    $logLines = array();

    if (file_exists($file)) {
    	$log = fopen($file, 'r');
        while (($logLine = fgetcsv($log)) !== FALSE) {
            array_push($logLines, $logLine);
         }
         fclose($log);
    }else{
    	$error = true;
    	$message = "No se ha podido leer el fichero de log o aún no existe.";
    }

    $result = array(
			'error' => $error,
			'message' => $message,
			'response' => $logLines
		);

	$result = json_encode($result);
	echo $result;

}catch(Exception $e){
     $result = array(
			'error' => true,
			'message' => "No se ha podido leer el fichero de log o aún no existe."
		);

	$result = json_encode($result);
	echo $result;
}
