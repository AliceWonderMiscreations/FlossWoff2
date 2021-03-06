Font Server Setup and Install
=============================

If you want to run the font server software here as your own font server, your
server needs to have the following features:

* PHP 7.1 or newer (might work on 7.0 but not tested)
* Redis database server
* PECL Sodium extension (usually present with PHP 7.2, needs to be installed
  for PHP 7.1 and some 7.2 builds)
* PECL Redis extension

I recommend Apache 2.4 series. If you use 2.2 series, your Apache is seriously
outdated. If you use a different HTTP server, you will need to customize the
`.htaccess` file that is part of this project.

To run a font server, do a `git clone` of this project:

    git clone https://github.com/AliceWonderMiscreations/FlossWoff2.git

In the event there is more than one branch, master is what you want and master
is what you get with that command, other branches will only be used for testing
stuff that may not be stable.

The `www` directory should be your Apache `DocumentRoot`.

The `csscache` directory needs to be writeable by the web server. That is where
it caches style sheets it creates so it can respond faster to queries that
would result in the same style sheet being generated.

The `phpinc` directory is where you can put some custom stuff to change the
behavior of the font server.

Once you have the git, run the following command within the `FlossWoff2`
directory to install the dependencies from composer:

    composer install --no-dev

You can leave off the `--no-dev` but it results in a bunch of extra crap being
installed that you do not need.

Cache Key Generation
--------------------

This project uses an encrypted Redis PSR-16 cache. Strictly from a privacy
point of view the cache does not need to be encrypted, but since most servers
use Xeon and Xeon has AES-NI in the CPU that makes AES encryption vey very
fast, I believe it is best practice to encrypt memory and database cache just
in the event they ever do end up being used for something in the future where
it would hurt privacy or security if there was a cache leak.

Anyway, you will need to make a configuration file that holds the encryption
key for the cache.

Run the following command inside the `FlossWoff2` directory to do so:

    vendor/bin/makeRedisSodiumConfig FONTSERVECACHE

You can alternatively copy the existing `FONTSERVECACHE.dist.json` to
`FONTSERVECACHE.json` but that would mean you are using a private key that is
not very private, since it is
[public on github](https://github.com/AliceWonderMiscreations/FlossWoff2/blob/master/FONTSERVECACHE.dist.json).

Inside the configuration file, it is okay to change the

    "strict": false

to

    "strict": true

This code is written clean, the `makeRedisSodiumConfig` utility defaults to
false because PSR-16 does not mandate strict enforcement of types, so to work
as a drop in replacement for other PSR-16 cache interfaces, it has to default
to a mode that does not mandate strict enforcement of types. But FlossWoff2 is
careful about types.


Apache Configuration
--------------------

If at all possible, use a domain that does not ever use cookies. If your domain
(or a parent domain) has cookies saved in the client browser, they will be sent
with the request for the CSS file and the fonts, making it look like it could
be tracking the user to privacy software.

Easiest thing to do is register a domain for which your company has a strict
policy that the domain or sub-domains can *never* set cookies.

The font server is a domain users do not need to see, so you do not need to
spend a lot of time thinking about it. Just use a `.com` as they are the
cheapest to renew.

That is what I am doing with `trippyid.com` - it is not a very good domain name
but it does not exist for users to ever need to see, it exists for resources
used by other websites and to never ever set any cookies.

### Use TLS

I highly __highly__ *highly* recommend you use TLS (the protocol formerly known
as Prince, er, I mean SSL) for the font server. It does not make sense anymore
to run servers that are not TLS.

Given that [Let's Encrypt](https://letsencrypt.org/) provides free signed TLS
certificates, there really is not any excuse not to deploy every website with
TLS.

Since the font server is a domain users will never have to manually enter into
their browser, do not bother redirecting Port 80 traffic to Port 443. Just do
not listen on Port 80.

A sample Apache configuration file: [fontserver.conf](fontserver.conf)

This is the SSLLabs rating of that configuration:

[https://www.ssllabs.com/ssltest/analyze.html?d=fonts.trippyid.com](https://www.ssllabs.com/ssltest/analyze.html?d=fonts.trippyid.com&latest)

Your results may differ depending on other TLS configuration parameters in your
apache install.

If you do not use Let's Encrypt because you do not like the idea of an
automated script that makes external connections making changes to your daemon
configuration files, I am the same way.

That is why I created this script:

[letsencrypt.sh](https://gist.github.com/AliceWonderMiscreations/de1a37b41df545eba3b6d6e77f6f29fb)

Note that that particular script only updates the private key if it is nearing
a year in age, that makes DANE easier. So when you regenerate the cert every
three months, do not update the private key in your Apache config to the new
date unless a new private key was actually generated.

I run that script manually and it creates the private key and gets the certs,
but it does not touch server configuration files. It also generates the DANE
fingerprints if your DNS is secured by DNSSEC and you want to use DANE to
secure your certificate.


Add Allowed Referrer Domains
----------------------------

The font server will refuse to serve the CSS file that defines fonts if the
requesting client sends a HTTP referer header containing a hostname that is not
white listed as allowed to use the font server.

This is done because I simply can not afford afford it, I am what people call
extremely poor. Lower class. And if you are an asshole, then clearly I am a
lazy bum who brought it on myself, it has nothing to do with circumstances that
are beyond my control. But anyway, I simply can not afford to pay for the
bandwidth commercial companies that profit will use if they use my font server.
So I will be charging a bandwidth fee to allow people to use my font server
with their web applications.

If running this font server on your own, you will need to maintain your own
white list of domains that are allowed to reference your font server when they
send a web page to a requesting client.

Copy the file `ReferrerWhitelist.dist.json` to `ReferrerWhitelist.json` and
then edit the file to specify the hostnames allowed to use your font server as
well as an expiration date for when they are no longer allowed to. If you do
not ever want permission for a given hostname to expire, just use a date far
off in the future, e.g. in the year 2112.

The `hostname` parameter must be a valid hostname. The `expires` parameter must
be a date that the PHP `strtotime()` function knows what to do with.

From within the `FlossWoff2` directory run the command:

    php utilities/loadReferrerWhitelist.php

That will load the entries from the JSON flat file into the Redis database
cache.

### Important Note

Whenever you change the `hexkey`, `prefix`, or `salt` configuration parameter
in your `FONTSERVECACHE.json` file you __must__ run the
`utilities/loadReferrerWhitelist.php` utility again. Changing any of those
parameters has the equivalent effect of deleting the Redis cache.

### Removing a Host From the Whitelist

To remove a host from the whitelist, first make sure that the hostname has been
removed from the `ReferrerWhitelist.json` file so that it does not accidentally
get added again. Then run the following utility from within the `FlossWoff2`
directory:

    php utilities/remove_referrer.php whatever.com

where `whatever.com` is the hostname to be deleted.

Please note that the `utilities/loadReferrerWhitelist.php` utility only adds
hosts with an expiration date in the future and that the `ServeStyle.php`
script that generates a CSS file will automatically remove any host with an
expiration date three weeks in the past, so you do not need to do anything
special when you want a host removed automatically as long as the expiration
date was properly set.


Garbage Collection
------------------

Over time, stale cached CSS files will build up. Please see the documentation
for [File Cache Maintenance](../utilities#file-cache-maintenance)
for instructions on how to automate cleanup of old stale cache files.


Adding Fonts
------------

The font server as the code is on github only has a small set of fonts.

Documentation for adding more fonts to come.











