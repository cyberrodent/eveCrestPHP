<?php

class character {
        public static function get($e, $id) {
                $i = $e->makeRequest("/characters/" . $id . "/", "", true);
                return $i;
        }
}

