<?php
// Copy this file to config.local.php on the server and enter your 20i MySQL details.
// DO NOT commit config.local.php to Git.

return [
    'db_host' => 'localhost',
    'db_name' => 'YOUR_DATABASE_NAME',
    'db_user' => 'YOUR_DATABASE_USER',
    'db_pass' => 'YOUR_DATABASE_PASSWORD',
    'db_charset' => 'utf8mb4',

    // Optional security setting
    'session_timeout_seconds' => 3600
];
