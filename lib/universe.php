<?php

class Universe {
    public static function getLocation($e, $location_id) {
        $location = $e->makeRequest("/universe/locations/{$location_id}/", "", true); 
        return $location;
    }
}
