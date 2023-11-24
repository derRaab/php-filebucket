# php-filebucket

A very basic PHP and Apache based temporary file storage API.


## Installation

`git clone https://github.com/derRaab/php-filebucket`

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




