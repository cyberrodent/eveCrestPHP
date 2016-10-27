<?php

class market_types {

        public static function getAll($e) {
                $i = $e->makeRequest("/market/groups/", "", true);
                return $i;
        }

        public static function get($e, $id) {
                $i = $e->makeRequest("/market/groups/$id/", "", true);
                return $i;
        }
}




/* Here is an example market type:
            [811] => stdClass Object
                (
                    [marketGroup] => stdClass Object
                        (
                            [href] => https://crest-tq.eveonline.com/market/groups/1322/
                            [id] => 1322
                            [id_str] => 1322
                        )

                    [type] => stdClass Object
                        (
                            [id_str] => 2549
                            [href] => https://crest-tq.eveonline.com/types/2549/
                            [id] => 2549
                            [name] => Lava Command Center
                            [icon] => stdClass Object
                                (
                                    [href] => http://imageserver.eveonline.com/Type/2549_64.png
                                )

                        )

                    [id] => 2549
                    [id_str] => 2549
                )
 */
 
