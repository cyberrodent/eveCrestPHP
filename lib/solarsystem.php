<?php

class Solarsystem {
    public static function get($e, $solarsystem_id) {
        return $e->makeRequest("/solarsystems/{$solarsystem_id}/", "", true);
    }
}
