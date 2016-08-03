<?php

require_once 'connMongoDB.php';
require_once 'entities.php';

$error = false;
$message = "";
$response = array();

$conn = new MongoConn();
$mongo = $conn->connect();
if ($mongo) {
	//DB selection
	$db = $mongo->selectDB("gamesly");

	//Collection selection
	$collection = $db->selectCollection("Notice");

	$notice = new Notice($collection);

	$noticeList = $notice->listNotices();
	foreach ($noticeList as $notice) {
		array_push($response, $notice);
	}
	
}else{
	$error = true;
	$message = "Hay un problema con la base de datos, pruebe de nuevo en unos minutos";
}

$result = array(
	'error' => $error,
	'message' => $message,
	'response' => $response
);

$result = json_encode($result);
echo $result;