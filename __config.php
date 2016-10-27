<?php

// Configure to load classes from a relative `lib` directory
spl_autoload_register(function ($class_name) {
            include __DIR__ . "/lib/" . lcfirst($class_name) . '.php';
});

// For this to work you need to register an app with CCP
// Enter the ID and SECRET for your app to allow authentication
const CLIENT_ID     = ""; // <-- put yours in here
const CLIENT_SECRET = ""; // <-- and here

define('REFRESH_TOKEN',  unserialize(file_get_contents(__DIR__ . "/.eve_sso_refresh_token.txt")));

require_once __DIR__ . "/lib/utilities.php";
