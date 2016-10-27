<?php
require_once "./__config.php";

// #################################################################
//                              SSO SETUP
// #################################################################
// This comes after you've done the SSO
// See the files under the sso_setup directory for how
// to enable SSO for this app.

// The value for this is read in __config.php
$refresh_token = REFRESH_TOKEN;

// #################################################################
/// MAIN
// #################################################################

// Whatever else you do, you're going to need one of these
$e = new eveCrest($refresh_token);


// These will be input arguments - hard coded for now
$region_id = 10000042;       // Metropolis
$location_filter = 60005686; // Hek; show only orders in this location
$location_filter = 0;        // zero (0) will mean don't filter

// These will collect here till such time as this script accepts arguments
$market_group_id = 206 ;        // 5 is Frigates;
$market_group_id = 1855 ;       // ice ores
$market_group_id = 1372;        // Destroyers too, what's the difference?  This is ALL TYPES OF Dessies
$market_group_id = 582;         // Destroyers  -  this is the standard Destroyers

$market_group_id = 1857 ;       // 18 is Minerals



$mgsg = new MarketGroupSummaryGenerator($e, $region_id, $market_group_id, $location_filter);
$report = $mgsg->main();
print $report;



