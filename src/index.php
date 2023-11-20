<?php

// Available job types
$job_download = 'download';
$job_upload = 'upload';

// Check if a valid job is requested
$job = $_POST['job'];
if($job != $job_download && $job != $job_upload) {
    header('HTTP/1.0 400 Bad Request');
    echo("Invalid job");
    exit;
}

?>