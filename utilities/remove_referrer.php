#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**
 * Utility to remove host from white list of domains that are allowed to use the font server.
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

$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

// initiate cache
$config = dirname(dirname(__FILE__)) . '/FONTSERVECACHE.json';
if (! file_exists($config)) {
    echo "Could not find the SimpleCache configuration file at " . $config . "\n";
    exit(1);
}

if (isset($argv[1])) {
    $cache = new SimpleCache($redis, $config);
    $hostname = trim(strtolower($hostname));
    if (function_exists('idn_to_ascii')) {
        $hostname = idn_to_ascii($hostname);
    }
    $cache->delete('exdate-' . $hostname);
    echo $hostname . " removed from white list.\n";
}

?>