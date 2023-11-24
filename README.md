# php-filebucket

A very basic PHP and Apache based temporary file storage API. As of now, only the upload job is implemented and returns the download URL.


## Installation

`git clone https://github.com/derRaab/php-filebucket`

## Update

`git fetch --all && git reset --hard origin/main`

## Setup

- `/public` has to be configured as the public server root.
- `/src/access.php` needs to be created as a copy of `access-sample.php` and contains your authentication tokens.

### access.php

```
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
```




