<?php

// Access setup: token -> job_type -> extensions
const ACCESS_TOKENS = [
    'YOUR_TOKEN_HERE' => [
        'download' => [
            'extensions' => [ 'jpeg', "jpg", "png", "gif", "zip" ]
        ],
        'upload' => [
            'extensions' => [ 'jpeg', "jpg", "png", "gif", "zip" ]
        ],
    ],
];

const PUBLIC_TOKEN_INJECT_STRING = 'YOUR_RANDOM_STRING_HERE';
const PUBLIC_TOKEN_INJECT_POS = 0;

?>
