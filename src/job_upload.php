<?php



// Constants
include_once('constants.php');



// Check if a file available
if (!isset($_FILES[FILES_FILE_1])) {
    header('HTTP/1.0 422 Unprocessable Content');
    echo("Upload file not provided");
    exit;
}



// Check if any file extension is allowed for upload
$upload_settings = $access_token_settings[JOB_TYPE_UPLOAD];
$upload_extensions = $upload_settings['extensions'];
if(!is_array($upload_extensions) || count($upload_extensions) == 0 ) {
    header('HTTP/1.0 500 Internal Server Error');
    echo("No extensions allowed for upload");
    exit;
}



// Read upload file info
$file_1 = $_FILES[FILES_FILE_1];
$file_1_name = $file_1["name"];
$file_1_tmp_name = $file_1["tmp_name"];
$file_1_extension = strtolower(pathinfo($file_1_name,PATHINFO_EXTENSION));




// Check if the file extension is allowed for upload
if(!in_array($file_1_extension, $upload_extensions) && !in_array("*", $upload_extensions)) {
  header('HTTP/1.0 403 Forbidden');
  echo("File extension $file_1_extension not allowed for upload.");
  exit;
}




// Basic check for valid image files
if(in_array($file_1_extension, IMAGE_EXTENSIONS) && getimagesize($file_1_tmp_name) === false) {
  header('HTTP/1.0 403 Forbidden');
  echo("Invalid image file detected.");
  exit;
}




// Data utils
include_once('data_utils.php');




// Create a new file id and name
$target_filename = create_next_file_id() . "." . $file_1_extension;
$target_file = FILES_DIR . '/' . $target_filename;




// Move the uploaded file to the target location
if (!move_uploaded_file($file_1_tmp_name, $target_file)) {
  mkdir(FILES_DIR, 0777, true);
  if (!move_uploaded_file($file_1_tmp_name, $target_file)) {
    header('HTTP/1.0 500 Internal Server Error');
    echo("Failed to move uploaded file");
    exit;
  }
}




// Access settings
include_once('access.php');




// Create a public token for the file
$public_token = create_public_token($target_filename, PUBLIC_TOKEN_INJECT_STRING, PUBLIC_TOKEN_INJECT_POS);
$public_url = "https://$_SERVER[HTTP_HOST]/files/$target_filename" . "?download=" . $public_token;




// Return the public url
header('HTTP/1.0 200 OK');
echo( $public_url );
exit;


?>
