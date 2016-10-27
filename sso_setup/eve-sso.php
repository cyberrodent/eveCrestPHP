<?php

/* This is the script at the redirect URL for Eve-SSO
 * It will get an auth token an request an access token
 * and print the access token to the browser so you 
 * take it to do other stuff
 */



// These are the specifics for the app you register
$CLIENT_ID = "";
$CLIENT_SECRET = "";

// To debug we can write stuff to this log
$outfile = "/tmp/eve_sso.log";



$code = $_GET['code'];

if (empty($code)) {
        echo "No Code to use<p>";
        die("Something went wrong");
}

$log = $code;
file_put_contents($outfile, date(DATE_RFC2822) . ":" . $log . "\n");


// NOW POST THE code back to get an access token

$auth_hash = base64_encode($CLIENT_ID . ":" . $CLIENT_SECRET);
$vars = array(
    'grant_type' => 'authorization_code',
    'code' => $code
);

$var_string = http_build_query($vars);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $var_string);  //Post Fields
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$headers = array();
$headers[] = "Authorization: Basic {$auth_hash}";
$headers[] = "Content-Type: application/x-www-form-urlencoded";
$headers[] = "Host: login.eveonline.com";

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$server_output = curl_exec ($ch);
curl_close ($ch);

print "<h1>Here is your token information from Eve-Online</h1>";
print "<pre>";
print  $server_output ;
print "</pre>";









