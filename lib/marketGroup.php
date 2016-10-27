<?php

class MarketGroup {

    public static function getMarketGroups($e) {
        $market_groups = $e->makeRequest("/market/groups/", "", true);
        return $market_groups;
    }


    public static function getMarketGroupMembers($e, $market_group_id) {
        $market_group_members = $e->makeRequest("/market/types/?group=https://crest-tq.eveonline.com/market/groups/{$market_group_id}/", "", true);
        return $market_group_members;

    }


}
