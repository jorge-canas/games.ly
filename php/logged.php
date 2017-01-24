<?php
	include_once('functions.php');
	
	if ( is_session_started() === FALSE ) session_start();
	if(isset($_SESSION['adminId']) || isset($_SESSION['userId'])){
		
	}else{
		header("location:/games.ly/index.html");
	}
