<?php

class Region {
    public static function get($e, $region_id) {
        return $e->makeRequest("/regions/{$region_id}/", "", true);
    }
}
