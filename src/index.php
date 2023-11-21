<?php




// Constants
include_once('constants.php');




// Only allow https
if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
    header('HTTP/1.0 400 Bad Request');
    echo("HTTPS only");
    exit;
}




// Check if a supported job type is set
$job_type = $_POST[POST_JOB_TYPE];
if($job_type != JOB_TYPE_DOWNLOAD && $job_type != JOB_TYPE_UPLOAD) {
    header('HTTP/1.0 400 Bad Request');
    echo("Invalid job type");
    exit;
}




// Check if a Bearer token is sent
$request_token = null;
if (isset($_SERVER['Authorization'])) {
    $request_token = trim($_SERVER["Authorization"]);
}
else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $request_token = trim($_SERVER["HTTP_AUTHORIZATION"]);
} elseif (function_exists('apache_request_headers')) {
    $requestHeaders = apache_request_headers();
    if (isset($requestHeaders['Authorization'])) {
        $request_token = trim($requestHeaders['Authorization']);
    }
}

if (!empty($request_token)) {
    if (preg_match('/Bearer\s(\S+)/', $request_token, $matches)) {
        $request_token = $matches[1];
    }
}

if($request_token == null) {
    header('HTTP/1.0 401 Unauthorized');
    echo("Invalid authorization token");
    exit;
}




// Constants
include_once('access.php');

// Check for an existing access setup
if(!defined('ACCESS_TOKENS')) {
    header('HTTP/1.0 500 Internal Server Error');
    echo("Invalid access setup");
    exit;
}




// Check if the token exists
if(!isset(ACCESS_TOKENS[$request_token])) {
    header('HTTP/1.0 401 Unauthorized');
    echo("Invalid token");
    exit;
}




// Check if the token is allowed to do the job
$access_token = ACCESS_TOKENS[$request_token];
if (!isset($access_token[$job_type])) {
    header('HTTP/1.0 401 Unauthorized');
    echo("Invalid token job type");
    exit;
}




// Execute jobs
if ($job_type == JOB_TYPE_DOWNLOAD) {
    include('job_download.php');
} else if ($job_type == JOB_TYPE_UPLOAD) {
    include('job_upload.php'); 
}
else {
    header('HTTP/1.0 501 Not Implemented');
    echo("Job not implemented");
    exit;
}

?>
