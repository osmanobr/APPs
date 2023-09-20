<?php

// Enable CORS (http://enable-cors.org/server_php.html)
header('Access-Control-Allow-Origin: *');

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  exit;
}

// This is a sample of data, which can become from a database
// for example. The point is that we can return here an associated
// array, which later "json_encode" convert into the right JSON.

$data = array(
  'firstName' => 'Osmano',
  'lastName' => 'Torres'
);

// Set the appropriate header (we returns a JSON object)

header('Content-Type: application/json');

// And echo the JSON as the response of this script.
echo json_encode($data);

// Exit the script execution here.
exit;