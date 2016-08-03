<?php
/**
* @return bool
*/
//Comprueba si la sesión está iniciada
function is_session_started(){
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}

function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }

    return $ip;
}

function addLogLine($action, $username = 'Desconocido'){
	$ip = getUserIP();
	$file = fopen("../log/log.csv", "a");
    fwrite($file,"".date("Y-m-d H:i:s").",".$username.",".$ip.",".$action.PHP_EOL);

    fclose($file);
}