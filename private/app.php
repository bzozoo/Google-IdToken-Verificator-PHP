<?php
require_once 'vendor/autoload.php';
if(isset($_SERVER['HTTP_ORIGIN'])){
  header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN'] . "");
}

header('Content-Type: application/json; charset=utf-8');
$reqBody = json_decode(file_get_contents('php://input'));

if(empty($reqBody->clientId) && empty($reqBody->idtoken)){
	$errorstring = '{"errormessage":"Bad arguments"}';
	echo json_encode(json_decode($errorstring), JSON_PRETTY_PRINT);
	return;
} else {
	$CLIENT_ID = $reqBody->clientId;
	$id_token = $reqBody->idtoken;
	echo idtokenverify($CLIENT_ID, $id_token);
}

function idtokenverify($CLIENT_ID = null, $id_token = null){
	$client = new \Google\Client(['client_id' => $CLIENT_ID]);
	$payload = $client->verifyIdToken($id_token);

	if ($payload) {
		return json_encode($payload, JSON_PRETTY_PRINT);
	} else {
		$errorstring = '{"errormessage":"Invalid ID token"}';
		return json_encode(json_decode($errorstring), JSON_PRETTY_PRINT);
	}
}
