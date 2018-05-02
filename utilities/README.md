EXPRIMENTAL
===========

The following PHP shell scripts exist to help you manage the font
server:

* [`loadReferrerWhitelist.php`](#load-referrer-whitelist)
* [`remove_referrer.php`](#remove-referrer)
* [`clearCache.php`](#clear-redis-cache)


Load Referrer Whitelist
-----------------------

The `loadReferrerWhitelist.php` reads the `ReferrerWhitelist.json` file and
adds the hosts defined in that file to the Redis database cache whitelist of
hosts that are allowed to use the font server.

Please note that clients who do not send a HTTP referer header or who send an
empty HTTP referer header will not be blocked, but clients that send a HTTP
referer header that contains a host name not in the Redis database cache will
be sent a `HTTP 402` error instead of the requested CSS file.

The `ReferrerWhitelist.json` file is of the following format:

    {
        "whitelist": [
            {
                "hostname": "example.org",
                "expires": "January 29, 2112"
            },
            {
                "hostname": "somewhere.net",
                "expires": "04-12-2019"
            }
        ]
    }

The `hostname` value must be a valid hostname and the `expires` parameter must
be a date string in the future that the php `strtotime()` function can turn
into a UNIX timestamp.

When the utility successfully adds a host to the Redis database cache, it gives
output letting you know the host was added to the cache.

If the utility is not able to add a particular host, it does not give any
output related to that host. Not very user friendly, but the utility was
written for me. Pull requests welcome. As is money if I am to improve it.

### Usage

This utility does not take any arguments. To run it, make sure you have
generated the `FONTSERVECACHE.json` file and that the `ReferrerWhitelist.json`
file exists with the entries you want it to have. Then run the following
command from the command line:

    php utilities/loadReferrerWhitelist.php

### WARNING

You need to re-run this script *after* making any edits to the
`FONTSERVECACHE.json` file.


Remove Referrer
---------------

The previous script only adds and/or updates the Redis database cache, it does
remove hosts from the Redis database cache.

If you need to specifically remove a host from the Redis database cache, use
the `remove_referrer.php` script. It takes a single argument, the domain to
remove from the whitelist.

### Usage

To use this utility, from the command line:

    php utilities/remove_referrer.php whatever.com

where `whatever.com` is the host to be removed from the whitelist.

### WARNING

If you do not also remove the entry for the host from the
`ReferrerWhitelist.json` file *and* it has a `expires` parameter in the future,
it might accidentally be reloaded.

When manually removing a host from the whitelist, it is a good idea to make
sure the host is also deleted from the `ReferrerWhitelist.json` file.


Clear Redis Cache
-----------------

The `clearCache.php` command line script will completely empty the Redis
database cache. If you happen to be using Redis on the host for other things,
it should not impact those other things, it should only clear the entries
related to the Font Server. However no warranties or guarantees are given.

You probably will never need to run this utility, but if you frequently
regenerate the `FONTSERVECACHE.json` file (which there is no reason to do)
then over time, whitelist records that are useless but never expire can build
up.

### Usage

This utility does not take any arguments. To run it, from the command line:

    php utilities/clearCache.php

All cache entries in the `FONTSERVECACHE` web-app namespace will be cleared.

### WARNING

After running this command, your whitelist will be nuked, so be sure to then
immediately run the `loadReferrerWhitelist.php` utility to repopulate it.
