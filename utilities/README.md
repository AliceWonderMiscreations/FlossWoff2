EXPRIMENTAL
===========

The following PHP shell scripts exist to help you manage the font
server:

* [`loadReferrerWhitelist.php`](#load-referrer-whitelist)
* [`remove_referrer.php`](#remove-referrer)
* [`clearCache.php`](#clear-redis-cache)
* [`fileCacheMaintenance.php`](#file-cache-maintenance)


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


File Cache Maintenance
----------------------

The font server creates CSS files out of static CSS files that correspond with
the requested font. Rather than do this every time, it caches the combined file
using a unique generated name on the filesystem and then caches the name of
that file in the Redis database cache in association with the request.

If the modification time stamp of any of the static files used to generate the
filesystem cached style sheet ever changes, then it becomes stale because a new
generated file with a different unique generated name will be generated.

Over time, the `csscache` directory can end up containing a lot of outdated
cached files that will literally *never* be accessed again. Garbage collection
is needed to remove them.

As those files are created by the user the web server daemon runs as, they can
only be deleted by the user the web server daemon runs as or by the root user.

An automated garbage collection should really never be run by the root user,
system administrators commonly do such things but it is dangerous, a slight
mistake or bug and very very very bad things can happen.

The utility script `fileCacheMaintenance.php` will do the job if run as the
right user.

What the script does, it looks through the `csscache` directory. When the
filesystem supports access time metadata, it looks at the last time the cached
file was accessed. For performance reasons (especially with NFS) some servers
disable file access time metadata on the filesystem. In those cases, it looks
at the modification time.

In either case, the result is a modification time.

A trigger time is randomly selected between 3 months and 7 months for which the
cached file is simply deleted if it is older than than that trigger time. The
reason the trigger time is random is to spread out the deletion of files that
were created close to the same just in case they are not actually stale and
will be needed. When a needed file has been deleted, it has to be re-created,
so it is good to spread out the deletion of lots of files created at about the
same time.

Cached file with an access (or modification when access not supported) time
within the last 90 days will never be deleted, and cached files with an access
(or modification) time more than 210 days ago will always be deleted. Between
those two parameters, it is random with deletion more likely the older longer
ago the access (or modification) time is.

You probably only need to run it once a month. Put something like the following
in your `/etc/cron.monthly/` directory:

    #!/bin/sh
    
    su -l apache -s /bin/bash --command="/usr/bin/php /srv/fonts.trippyid.com/FlossWoff2/utilities/fileCacheMaintenance.php"
    
    exit 0
    
Name it whatever you like, but it should be executable.

The `su -l apache -s /bin/bash` says to switch to the `apache` user for what
follows. The reason for the `-s /bin/bash` is because the `apache` user usually
has its shell set to `/sbin/nologin` so it needs that changed for the command.

Change the `apache` to whatever user your webserver daemon runs as.

The `--command="stuff"` says that we are only running the command `stuff` as
the specified user, and then it will exist.

For the `stuff` it is the command
`/usr/bin/php /srv/fonts.trippyid.com/FlossWoff2/utilities/fileCacheMaintenance.php`.

The `/usr/bin/php` needs to be the full path to your PHP 7+ interpreter.

The `/srv/fonts.trippyid.com/FlossWoff2/utilities/fileCacheMaintenance.php`
needs to be the full path to the `fileCacheMaintenance.php` utility script.

That will allow the garbage collection of stale cache files to take place.


-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
__EOF__