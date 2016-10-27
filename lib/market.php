<?php


class market {
        public static function getAll($e) {

                $i = $e->makeRequest("/market/", "", true);
                return $i;
        }

        public static function get($e, $region_id, $item_type_id, $buy_or_sell = "buy") {
                if ($buy_or_sell !== "buy") {
                        $buy_or_sell = "sell";
                }
                $item_type_href = "?type=https://crest-tq.eveonline.com/types/" . $item_type_id . "/"; 
                $i = $e->makeRequest("/market/{$region_id}/orders/{$buy_or_sell}/{$item_type_href}", "", true);
                return $i;
        }
}

