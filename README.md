# Eve Crest App


## Vision

This started out from curiosity about the CREST API. I wanted to build a
little framework to allow me to query different aspects of the eve
universe.  The CREST API is what it is and it is very straightforward so
this is largely a wrapper around those calls.  It adds in some caching and
handles the pagination and handles the SSO.


## Core Application

The EveCrest class provided in lib/eveCrest provides a makeRequest method
to make requests to the CrestAPI. Your application will need to pass in
an EveCrest object to its static methods.  I am not in love with this
pattern but it made things easy to get started.

The core system offers a cache to reduce pressure on the upstream
service.  This is also to provide some level of resilience should the
upstream be unavailable, requests can still be answered from the cache.

The core system can return the JSON directly or it can decode it and
return a native php datastructure.


## SSO

The scripts will gain access to CREST by using the SSO system.  There are
some scripts in ```./sso_setup/``` that can help you set this up so your
scripts have access to CREST.  The app will store and read a serialized
refresh token on disk in .eve_sso_refresh_token.txt.

To use these scripts you will also need to register an app with CCP and
obtain a CLIENT_ID and CLIENT_SECRET that will need to be entered into
__config.php.

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
        "url": "git@bitbucket.org:cyberrodent/eve-crest.git",
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
and invoke composer install.  

You may need to work out the loading and how you want to provide access
to credentials, make sure the cache directory is writeable etc.

While not at all seamless, this can be done you can build other
applications on top of this.






