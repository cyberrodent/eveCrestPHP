<?php

// any entry point will need these 3 lines
require_once "./__config.php";
$refresh_token = REFRESH_TOKEN;
$e = new eveCrest($refresh_token);

// You can use this script and grep to figure out the market group id of
// a thing you are interested in.

$market_groups = MarketGroup::getMarketGroups($e);

foreach ($market_groups->items as $g) {
        print $g->id . "\t" . $g->name . "\n";

}
