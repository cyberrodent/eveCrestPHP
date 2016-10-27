<?php

class regions {
        public static function get($e) {
            // There aren't that many regions to worry about pagination
            return $e->makeRequest("/regions/","", true);
    }
}

