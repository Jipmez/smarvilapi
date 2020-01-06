<?php
include('searchFunct.php');
header('Content-type:application/json;charset=utf-8');
$request=json_decode(file_get_contents('php://input'),true);
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type,access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
$clas = new Search('localhost','root','','smrvil');


if($_GET['key'] == 'cassinta'){

  if($_GET['subject'] == 'listings'){
    $jay = $clas->getlogdes();
    echo json_encode($jay);
  }
  if($_GET['subject'] == 'roommates'){
    $jay = $clas->getRoom();
    echo json_encode($jay);
  }
  $options = array(
    'http' => array(
      'method'  => 'POST',
      'content' => json_encode( $data ),
      'header'=>  "Content-Type: application/json\r\n" .
                  "Accept: application/json\r\n"
      )
  );
  
  $context  = stream_context_create( $options );
  $result = file_get_contents( $url, false, $context );
  $response = json_decode( $result );
  $data = array(
    'userID'      => 'a7664093-502e-4d2b-bf30-25a2b26d6021',
    'itemKind'    => 0,
    'value'       => 1,
    'description' => 'Boa saudaÁ„o.',
    'itemID'      => '03e76d0a-8bab-11e0-8250-000c29b481aa'
  );



  //API Url
  $url = 'http://example.com/api/JSON/create';
  
  //Initiate cURL.
  $ch = curl_init($url);
  
  //The JSON data.
  $jsonData = array(
      'username' => 'MyUsername',
      'password' => 'MyPassword'
  );
  
  //Encode the array into JSON.
  $jsonDataEncoded = json_encode($jsonData);
  
  //Tell cURL that we want to send a POST request.
  curl_setopt($ch, CURLOPT_POST, 1);
  
  //Attach our encoded JSON string to the POST fields.
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
  
  //Set the content type to application/json
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
  
  //Execute the request
  $result = curl_exec($ch);







//Make sure that it is a POST request.
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
    throw new Exception('Request method must be POST!');
}

//Make sure that the content type of the POST request has been set to application/json
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
    throw new Exception('Content type must be: application/json');
}

//Receive the RAW post data.
$content = trim(file_get_contents("php://input"));

//Attempt to decode the incoming RAW post data from JSON.
$decoded = json_decode($content, true);

//If json_decode failed, the JSON is invalid.
if(!is_array($decoded)){
    throw new Exception('Received content contained invalid JSON!');
}

//Process the JSON.



}





?>