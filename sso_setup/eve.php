<?php

/*
 * Redirects a browser to set up for Eve-SSO
 *
 * This will forward to eve-sso.php which will print the token info
 * so that you can use it in other things
 */


### This is Auth info for the App you registered with CCP

$EVE_OAUTH_URL  = "https://login.eveonline.com/oauth/authorize";
$CLIENT_ID      = "";
$CLIENT_SECRET  = "";
$redirect_uri   = 'http://localhost/eve-sso.php'; // <-- set this to a page you can have a local script load
$scopes = array(
        "publicData",
        "characterLocationRead",
        "characterFittingsRead",
        "characterContactsRead",
        "characterStatsRead"
);
$scopes_string = urlencode(implode(" ", $scopes));


// ------------------------------------------------ // 

$query  = "response_type=code&redirect_uri={$redirect_uri}&client_id={$CLIENT_ID}&scope={$scopes_string}&state=1";

header('Location: ' . $EVE_OAUTH_URL . "?" . $query);

