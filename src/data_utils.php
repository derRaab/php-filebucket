<?php

const DATA_DIR = __DIR__ . "/../data";
const FILES_DIR = DATA_DIR . "/files";
const TASK_COUNT_TXT = DATA_DIR . "/file_count.txt";




function create_next_count() {
    $count_string = file_get_contents(TASK_COUNT_TXT);
    $count = intval($count_string); 

    $max_count = 9999999;
    $new_count = $count + 1;
    if ($max_count < $new_count) {
      $new_count = 1;
    }

    file_put_contents(TASK_COUNT_TXT, "" . $new_count);
    return $new_count;
}




function create_next_file_id() {
    $count_string = str_pad( "" . create_next_count(), 7, "0", STR_PAD_LEFT);
    $timestamp_string = gmdate("Y-m-d\TH-i-s\Z");
    $random_string = str_pad( "" . rand(0, 9999999), 7, "0", STR_PAD_LEFT);
    return $count_string . "--" . $timestamp_string . "--" . $random_string;
}




function create_public_token($token, $inject_string = '', $inject_pos = 0) {
    $token = str_pad(($token), $inject_pos, "X", STR_PAD_RIGHT);
    $token = substr($token, 0, $inject_pos) . $inject_string . substr($token, $inject_pos);
    return hash('sha256', $token);
}

?>
