<?php

class EveCrest {
        // Full URL with protocol of where oauth happens
        public $sso_url = "https://login.eveonline.com/oauth/token";

        // hostname of the API Server (no protocol) No slash at the end
        public $crest_hostname = "crest-tq.eveonline.com";

        // Our Curl Handler
        private $ch;

        // When we have an access token, we store it here
        private $access_token;


        // got to have some local storage / caching
        private $cache_path = "/tmp/eve_cache";

        public $use_cache = true;

        public $DEBUG = false;

        public $cache_ttl = 3600; // in seconds.  Cached data older than this will refreshed

        /**
         * Use the supplied refresh token to obtain an access token
         * Set up a curl handler that can be used for other work
         */        
        public function __construct($refresh_token) {

                // This just puts together a string we'll need later
                $this->crest_server = "https://{$this->crest_hostname}/";

                if (empty($refresh_token)) {
                    throw new Exception("No Refresh Token provided and this doesn't do the full Oauth Dance for you.");
                }

                if (empty($this->access_token)) {
                    $this->access_token = $this->_useRefreshToken($refresh_token);
                }

                if (empty($this->access_token)) {
                    throw new Exception("Still no access token after requesting one with refresh. Something is wrong.");
                }


                if ($this->use_cache && (!is_dir($this->cache_path) )) {
                    mkdir($this->cache_path);
                }

                $this->_setupCurlHandler();
        }

        private function _setupCurlHandler() {
                // Sets $this->ch

                $ch = curl_init();
                // We set the URL Later
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $headers = array();
                $headers[] = "Authorization: Bearer {$this->access_token}";
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                $headers[] = "Host: {$this->crest_hostname}";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $this->ch = $ch;
        }


        private function _useRefreshToken($refresh_token) {
                // Sets the private property $access_token and also return it just in case

                $auth_hash = base64_encode(CLIENT_ID . ":" . CLIENT_SECRET);
                $vars = array(
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $refresh_token
                );
                $var_string = http_build_query($vars);

                // TODO: Try to use a single CURL thingie
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->sso_url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $var_string);  //Post Fields
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $headers = array();
                $headers[] = "Authorization: Basic {$auth_hash}";
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                $headers[] = "Host: login.eveonline.com";

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $server_output = curl_exec ($ch);

                $response_data = json_decode($server_output);
                // print_r($response_data);
                curl_close ($ch);

                $this->access_token = $response_data->access_token;
                return $this->access_token;  // cause
        }

        /**
         * _checkForCachedData
         * given a key, if we find the locally cached data, return it or FALSE
         */
        protected function _checkForCachedData($path) {
            $key = $this->_makeCacheKey($path);
            $data = null;
            if ($data = @file_get_contents($key)) {
                    return $data;
            }
            return false;
        }
        protected function _storeCacheData($key, $data) {
                $path = $this->_makeCacheKey($key);
                file_put_contents($path, $data);
                if ($this->DEBUG) {
                    echo "Storing $key to local cache";
                }
        }
        protected function _makeCacheKey($key) {
            $hash = md5($key);
            $path = $this->cache_path . "/" . $hash . ".json";
            return $path;
        }

        public function makeRequest($path, $payload, $decode_json = false) {
                // TODO not everything will be a post
                // If there is a payload (array) then we can assume we'll post that data
                //  TODO: Handle POSTS -- for now we ONLY GET
                //  curl_setopt($this->ch, CURLOPT_POST, 1);


                if ($this->DEBUG) { echo "Fetching Path $path"; }

                if ($this->use_cache) {
                    // Look for this in a local cache
                        $response = $this->_checkForCachedData($path);
                        if ($response) {
                                if ($this->DEBUG) {
                                    echo "Got data for $path from local cache";
                                }
                                if ($decode_json) {
                                        $response = json_decode($response);
                                }
                                return $response;
                        }
                }

                curl_setopt($this->ch, CURLOPT_URL, $this->crest_server .  $path);
                $response = curl_exec($this->ch);
                // TODO : Error Handling

                if ($this->DEBUG) { echo "got response $response"; }


                if ($this->use_cache) {
                    $this->_storeCacheData($path, $response);
                }
                // If they want us to decode the data and give back php data structure....
                if ($decode_json) {
                        $response = json_decode($response);
                }


                return $response;
        }

        public function makePaginatedRequest($path, $payload, $decode_json) {
            // hmm. how do I want to make this work?
            // 1. make the regular request
                // 2. inspect the response ...
                //    - if  


        } 

}

