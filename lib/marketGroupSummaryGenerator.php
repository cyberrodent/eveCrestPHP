<?php

class MarketGroupSummaryGenerator {

    protected $region_id;
    protected $market_group_id;
    protected $location_filter = 0;


    public function __construct(
        $e,
        $region_id,
        $market_group_id,
        $location_filter = 0
    ) {
        $this->e = $e;
        $this->region_id = $region_id;
        $this->market_group_id = $market_group_id;
        $this->location_filter = $location_filter;
    }


    public function main() {
        $out = "";
        // First get a list of the items in this group
        $market_group_members = MarketGroup::getMarketGroupMembers($this->e, $this->market_group_id);
        // $market_group_members is now a list of all the Battleships or whatever market group you gave it



        // Loop over said list and fetch our data
        foreach ($market_group_members->items as $type) {

            // This is a little cheat to help create some fixture data to use for testing
            $save_fixtures = false;
            if ($save_fixtures) {
                $s_type = serialize($type);
                file_put_contents("./test/data/market_group_{$market_group_id}.txt", $s_type);
            }

            $r = new MarketTypeSummary($this->e, $type, $this->region_id, $this->location_filter);
            $r->generateSummary();
            $out .= $r->renderReport();
           
        }
        $heading = $r->makeReportHeader();

        return $heading . $out;
    }
}
