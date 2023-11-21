<?php




// Constants
include_once('constants.php');




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




// Read .env values
$env_path = dirname(__FILE__) . "/../.env";
if (!is_readable($env_path)) {
    header('HTTP/1.0 500 Internal Server Error');
    echo("Cannot read .env file");
    exit;
}
$lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) {
        continue;
    }
    list($name, $value) = explode('=', $line, 2);
    $name = trim($name);
    $value = trim($value);

    if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}




// Check if the token is allowed to do the job
if ($job_type == JOB_TYPE_DOWNLOAD) {
    $job_allowed_tokens = explode(',', $_ENV[DOTENV_DOWNLOAD_TOKENS]);
} else if ($job_type == JOB_TYPE_UPLOAD) {
    $job_allowed_tokens = explode(',', $_ENV[DOTENV_UPLOAD_TOKENS]);
} else {
    $job_allowed_tokens = [];
}

if (!in_array($request_token, $job_allowed_tokens)) {
    header('HTTP/1.0 401 Unauthorized');
    echo("Invalid token");
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
