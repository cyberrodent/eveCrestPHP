<?php

class Constellation {
    public static function get($e, $constellation_id) {
        return $e->makeRequest("/constellations/{$constellation_id}/", "", true);
    }
}
