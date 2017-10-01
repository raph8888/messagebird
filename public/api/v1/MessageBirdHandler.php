<?php

require(__DIR__ . '/../../../vendor/autoload.php');

include 'classes/SMS.php';

use App as messageBird;

try {

    // Make sure that it is a POST request.
    if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0) {
        throw new \Exception('Request method must be POST!');
    }

    // Make sure the content type of the POST request has been set to application/json
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    if (strcasecmp($contentType, 'application/json') != 0) {
        throw new \Exception('Content type must be: application/json');
    }

    // Handle json post request, get raw post data.
    // Less memory intensive alternative to $HTTP_RAW_POST_DATA
    $content = trim(file_get_contents("php://input"));

    // Attempt to decode the incoming raw post data from json.
    $decoded = json_decode($content, true);

    // If json_decode failed, the json is invalid.
    if (!is_array($decoded)) {
        throw new \Exception('Received content contained invalid JSON!');
    }

    $send_message = new messageBird\SMS();
    $response = $send_message->send($decoded);

} catch (\Exception $e) {
    // if an exception happened in the try block above
    $response = array(
        'success' => false,
        'result' => 'Unknown',
        'error' => $e->getMessage()
    );
}

// Calculates the execution time of the script.
// 1 message = float(0.15762090683)
// 2 messages (concatenated) = float(0.341706037521)
// 4 messages (concatenated) = float(0.564226865768)
$executionTime = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

exit(json_encode($response));