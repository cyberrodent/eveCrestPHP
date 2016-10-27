<?php
/**
 * try to keep this a dumb data class
 * just to store this data
 */
class MarketTypeSummaryReport {
    public $total_volume;
    public $total_volume_entered;
    public $average_price;
    public $min_price;
    public $max_price;
    public $orders;         // copy of the results from Crest
    public $location_filter;    // optional location_id; limit report to just this station
    public $region_id;
    public $type;            // The type of thing these are orders for
    public $order_type;         // "buy" or "sell" usually
}
