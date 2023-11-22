<?php

// Constants
include_once('constants.php');
// Data utils
include_once('data_utils.php');




// Check if the file exists
$filename = trim(substr($request_url['path'], strlen(JOB_TYPE_DOWNLOAD_PATHNAME)));
$file = FILES_DIR . '/' . $filename;
if (strlen($filename) === 0 || !file_exists($file)) {
    header('HTTP/1.0 404 Not Found');
    echo("File not found");
    exit;
}




// Access settings
include_once('access.php');




// Check if the provided download token matches
parse_str($request_url['query'], $params);
$download_token = $params['download'];
$public_token = create_public_token($filename, PUBLIC_TOKEN_INJECT_STRING, PUBLIC_TOKEN_INJECT_POS);
if (!hash_equals($public_token, $download_token)) {
    header('HTTP/1.0 401 Unauthorized');
    echo("Invalid download token");
    exit;
}




// Send the file to the client
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($file));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
ob_clean();
flush();
readfile($file);
exit;

?>




// https://php-filebucket.local/files/0000010--2023-11-21T15-50-59Z--3625113.jpg?download=5130f3f4caa7f22036b311b7c5c5157475900a41293eb4fd457d097db23ca668