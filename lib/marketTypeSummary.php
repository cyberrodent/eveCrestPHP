<?php

// This is not an EveCrest API class and maybe should be put 
// somewhere slightly different in the codebase.  (TODO)
//
// This class is designed to hold summary data about
// market orders for a given type in a given region
// on a particular timestamp

class MarketTypeSummary {

    protected $type;
    protected $region_id;
    protected $location_filter;
    public $orders;

    public $report = array();

    /**
     * we need $type to be an object matching
     * the Crest return type for elements of a 
     * market group
     *
     * We expect this type to have a type 
     * property and we'll read the href and name 
     * properties of that.
     */
    public function __construct($e, $type, $region_id, $location_filter) {
        $this->e = $e;
        $this->region_id = $region_id;
        $this->type = $type;
        $this->location_filter = 0; // For now
        $this->location_filter = $location_filter;
    }

    public function generateSummary() {
        $type_href = $this->type->type->href;

        $name = $this->type->type->name;
        $region_id = $this->region_id;
        $location_filter = $this->location_filter;

        $orders = [];
        foreach (['buy', 'sell'] as $t) {
            $this->orders[$t] = $this->e->makeRequest("/market/{$region_id}/orders/{$t}/?type={$type_href}", "", true);
        }

        $bcv = $this->countVolume($this->orders['buy'], 'buy', $this->type);
        $scv = $this->countVolume($this->orders['sell'], 'sell', $this->type);

        $this->report['buy'] = $bcv;
        $this->report['sell'] = $scv;
    }

    protected function countVolume($orders, $order_type, $type) {
        // Loops over a set of orders and extracts some data
        // returns a ridiculous array that I should clean up
        // if there is a location_filter set 
        //     then only return data from orders at that location.
    
        $location_filter = $this->location_filter;
        $total_volume_entered = 0;
        $total_volume = 0;
        $prices = array();
        $weighted_prices = array();

        foreach ($orders->items as $order) {
            if ($location_filter > 0) {
                if ($order->location->id != $location_filter) {
                    continue;
                }
            }

            $total_volume_entered += $order->volumeEntered;
            $total_volume += $order->volume;
            $prices[] = $order->price;
            $weighted_prices[] = $order->price * $order->volume;
        }

        $min_price = $max_price = $average_price = $weighted_average_price = $average_units_per_order = 0;

        $number_of_orders = count($prices);
        if ($number_of_orders > 0) {
                $average_price = round(array_sum($prices) / $number_of_orders);
                $weighted_average_price = round( array_sum($weighted_prices) / $total_volume);

                $min_price = round(min($prices));
                $max_price = round(max($prices));

                $average_units_per_order = round($total_volume / $number_of_orders);
        }

        $report = new MarketTypeSummaryReport();

        $report->region_id = $this->region_id;
        $report->number_of_orders = $number_of_orders;
        $report->average_order_size = $average_units_per_order;
        $report->total_volume = $total_volume;
        $report->total_volume_entered = $total_volume_entered;
        $report->weighted_average_price = $weighted_average_price;
        $report->average_price = $average_price;
        $report->min_price = $min_price;
        $report->max_price = $max_price;
        // this would add an array of the orders themselves
        // $report->orders = $orders;
        $report->type = $type;
        $report->order_type = $order_type;
        $report->location_filter = $location_filter;
        return $report;
    }

    public function makeReportHeader() {
        $out = "Market Group Report for: ";
        $out .= Region::get($this->e, $this->region_id)->name;
        $out .= "\n";

        if ($this->location_filter > 0) {
            $location = Universe::getLocation($this->e, $this->location_filter);
            $out .= "Result limited to system: ";
            $out .= $location->station->name . "\n"; 
        }

        $out .= "----------------------------------------\n";
        return $out;
    }

    public function renderReport($buy_or_sell = null) {
        $out = "";
        foreach (["sell", "buy"] as $ot) {
            $r = $this->report[$ot];
            $out .= str_pad(ucfirst($ot) . "ing " . $this->_filter_names($r->type->type->name) . ":", 38);
            $out .= str_pad("{$r->number_of_orders} orders", 15);
            $out .= str_pad(number_format($r->total_volume)  ." units to {$ot}", 30);
            

            $out .= str_pad("Average price: ". number_format($r->average_price), 30);
            $out .= str_pad("Average order size: ". number_format($r->average_order_size), 30);
            $out .= "\n";

        }
        return $out;
    }

    private function _filter_names($name) {
        $name = str_replace("Compressed", "Comp.", $name);
        return $name;
    }
}
