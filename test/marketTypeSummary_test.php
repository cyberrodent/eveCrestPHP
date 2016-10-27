<?php


// TODO: This needs to come from someplace else



/**
 * NOTE:
 *
 * This test will hit the real API (or your local cache)
 * and as such is not a pure unit test.  This loads some
 * fixture data but, so far at least, it also queries Crest
 * for real order data.  As such this test is inherently flakey
 * and should someday have all its external calls mocked up.
 */
class MarketTypeSummary_Test extends PHPUnit_Framework_TestCase {

    public $type; // Holds a market group type object

    public function setUp() {
        // Reads our market group type object as a fixture
        $this->type = unserialize(file_get_contents("./test/data/market_group_7.txt"));
    }

    public function testSmoke() {
        // This just proves that we can talk to Crest as configured with our REFRESH TOKEN
        // If this test fails, other tests that rely on the API will fail as well.

        $e = new EveCrest(REFRESH_TOKEN);
        $this->assertTrue(true);
    }

    public function testSummary() {
        // This test asserts that after generating a MarketTypeSummary object, it has a buy and a sell key
        // No more. No less.
        // Better testing is possible and please note the note at the top of this class

        $e = new EveCrest(REFRESH_TOKEN);
        $type = $this->type;
        $region = 10000042;
        $location_filter = 0;

        $s = new MarketTypeSummary($e, $type, $region, $location_filter);
        $s->generateSummary($e);

        $this->assertTrue(array_key_exists('buy', $s->orders));
        $this->assertTrue(array_key_exists('sell', $s->orders));
    }

}
