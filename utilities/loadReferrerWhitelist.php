#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**
 * Utility to generating white list of domains that are allowed to use the font server.
 *
 * @package AWonderPHP/FlossWoff2
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/FlossWoff2
 */

// SimpleCache Redis
require_once(dirname(dirname(__FILE__)) . '/vendor/psr/simple-cache/src/CacheException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/psr/simple-cache/src/CacheInterface.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/psr/simple-cache/src/InvalidArgumentException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/InvalidArgumentException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/StrictTypeException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/InvalidSetupException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/SimpleCache.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecacheredis/lib/SimpleCacheRedisSodium.php');

use \AWonderPHP\SimpleCacheRedis\SimpleCacheRedisSodium as SimpleCache;

date_default_timezone_set('America/Los_Angeles');

$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

// initiate cache
$config = dirname(dirname(__FILE__)) . '/FONTSERVECACHE.json';
if (! file_exists($config)) {
    echo "Could not find the SimpleCache configuration file at " . $config . "\n";
    exit(1);
}

$referrerConfig = dirname(dirname(__FILE__)) . '/ReferrerWhitelist.json';
if (! file_exists($referrerConfig)) {
    echo "Could not find the referrer configuration file at " . $referrerConfig . "\n";
    exit(1);
}

$json = file_get_contents($referrerConfig);

if (! $obj = json_decode($json)) {
    echo $referrerConfig . " does not appear to be a valid JSON file.\n";
    exit(1);
}

if (! isset($obj->whitelist)) {
    echo $referrerConfig . " does not contain a domain white list.\n";
    exit(1);
}

$whitelist = $obj->whitelist;

if (! is_array($whitelist)) {
    echo "Whitelist not an array.\n";
    exit(1);
}

$cache = new SimpleCache($redis, $config);

foreach ($whitelist as $white) {
    if (isset($white->hostname)) {
        $hostname = trim(strtolower($white->hostname));
        if (function_exists('idn_to_ascii')) {
            $hostname = idn_to_ascii($hostname);
        }
        if (isset($white->expires)) {
            $expires = $white->expires;
            if ($time = strtotime($expires)) {
                if ($time > time()) {
                    $ymd = date('Y-m-d', $time);
                    $cache->set('exdate-' . $hostname, $ymd);
                    echo "Expiration for " . $hostname . " set to " . $ymd . "\n";
                }
            }
        }
    }
}

?>