# Eve Crest PHP


## What is this?

This started out from curiosity about the CREST API. I wanted to build a
little framework to allow me to query different aspects of the eve
universe.  The CREST API is what it is and it is very straightforward so
this is largely a wrapper around those calls.  It adds in some caching 
and handles the SSO so that you can build on top of the data.

## Current Status

This is offered "As-Is". Use at your own risk. I am interested and happy
to hear if you have feedback but can make no assurances that this code
will be actively maintained.

## How does it work?

Define an eveCrest object that knows how to talk with CREST. This
object is then passed into a set of static methods that will do your
bidding.  Here is an example of fetching a list of all the MarketGroups.

```
<?php
 
    $e = new eveCrest($refresh_token);

    $market_groups = MarketGroup::getMarketGroups($e);
    foreach ($market_groups->items as $g) {
        print $g->id . "\t" . $g->name . "\n";

    }

```
The core system offers a cache to reduce pressure on the upstream
service.  This is also to provide some level of resilience should the
upstream be unavailable, requests can still be answered from the cache.
The cache is defined and can be tweaked a bit in in lib/eveCrest.php.

The core system can return the JSON directly or it can decode it and
return a native php datastructure.


## Setup

    1. Download.
    2. Composer install
    3. Copy __config.php.ORIGINAL to __config.php and add you secrets.
    4. cd sso-setup.  Add credentials where needed run php eve-sso to create
       a serialized refresh token.
    5. See app.php for basic usage.
    



## SSO

The scripts will gain access to CREST by using the SSO system.  There are
some scripts in ```./sso_setup/``` that can help you set this up so your
scripts have access to CREST.  The app will store and read a serialized
refresh token on disk in .eve_sso_refresh_token.txt.

To use these scripts you will also need to register an app with CCP and
obtain a CLIENT_ID and CLIENT_SECRET that will need to be entered into
__config.php.

This is all a bit of a pain but I suppose if you are this far into this
you know about these things.  The tooling here is not at all slick but
it will get the job done.  Patches welcome :)


## API

In the /lib directory we create class files for each domain of the Crest
API. (is "domain" the right word here?)

``` /lib/character.php ```

Each of these files provides a class with static methods to wrap up API
calls and do any pre-or-post processing.



```
<?php

class character {
    public static function get($e, $id) {
        $i = $e->makeRequest("/characters/" . $id . "/", "", true);
            return $i;
    }
}


```


## Composer

To load this as a library via composer you will need to privately host
this repository.

The SSO part will be messy to handle as you will need to somehow supply
the refresh token whereever composer installs this.  This is an area that
can be improved.

Add the repository and the pacakage to your projects composer.json:

```
"repositories": [
  {
  
  "type": "package",
    "package": {
      "name": "cyberrodent/eve-crest",
      "version": "0.0.1",
      "type": "package",
      "source": {
        "url": "WHEREEVER-YOU-HOST-YOUR-REPO",
        "type": "git",
        "reference": "master"
      }
    }
  }
],

"require": {
  "php": ">=5.5.0",
  "slim/slim": "^3.1",
  "slim/php-view": "^2.0",
  "monolog/monolog": "^1.17",
  "cyberrodent/eve-crest" : "0.0.1"
},
```
```
and invoke composer install  

You may need to work out the loading in your app and how you want to
provide access to credentials, make sure the cache directory is
writeable etc. 

While not at all seamless, this can be done you can build other
applications on top of this.



Patches welcome and Fly Safe.






