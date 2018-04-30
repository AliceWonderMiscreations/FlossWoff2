<?php
declare(strict_types = 1);

/**
 * Alternative to Google Fonts.
 *
 * This file finds and serves the appropriate CSS based upon the GET arguments.
 * This file is completely untested and probably broken.
 *
 * @package AWonderPHP\FlossWoff2
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/FlossWoff2
 */

// FileWrapper
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/InvalidArgumentException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/NullPropertyException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/TypeErrorException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/FileWrapper.php');
// SimpleCache Redis
require_once(dirname(dirname(__FILE__)) . '/vendor/psr/simple-cache/src/CacheException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/psr/simple-cache/src/CacheInterface.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/psr/simple-cache/src/InvalidArgumentException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/InvalidArgumentException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/StrictTypeException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/InvalidSetupException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/SimpleCache.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecacheredis/lib/SimpleCacheRedisSodium.php');


use \AWonderPHP\FileWrapper\FileWrapper as FileWrapper;
use \AWonderPHP\SimpleCacheRedis\SimpleCacheRedisSodium as SimpleCache;

/*
 * TODO - use a hash of the request uri as a key to see if matching cached CSS already is set
 *        and only calculate the matching CSS if it isn't set or cached CSS doesn't exist.
 *
 *        Use redis for this, not APCu, so it persists a server restart.
 */
 
$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

// initiate cache
$config = dirname(dirname(__FILE__)) . '/FONTSERVECACHE.json';
if (! file_exists($config)) {
    header('HTTP/1.0 500 Internal Server Error');
}
$cache = new SimpleCache($redis, $config);

// verify referring domain in white list. This is sent by client,
//  so the server including the link tag can't modify it.
//  If header not sent, always allow, some users turn it off
//  for privacy and that's okay. Also, it often is not sent when
//  the referring website is http and not https.
$referrer_host = '';
if (isset($_SERVER['HTTP_REFERER'])) {
    if ($referrer = parse_url($_SERVER['HTTP_REFERER'])) {
        if (isset($referrer['host'])) {
            $referrer_host = $referrer['host'];
        }
    }
}
$referrer_host = strtolower($referrer_host);

// NOTE - if there is a problem of webmasters intentionally creating
//  static CSS files to bypass this test, I will create a wrapper to
//  serving the fonts that also checks. However since all fonts are
//  free from Google Fonts, I suspect webmasters who don't want to
//  pay for my mirror will use them rather than be sneaky.
$servecss = true;
if (strlen($referrer_host) > 0) {
    $expires = $cache->get('exdate-' . $referrer_host);
    if (is_null($expires)) {
        $servecss = false;
    } else {
        // give a 3 week grace period
        $tstamp = strtotime($expires) + 1814400;
        if ($tstamp < time()) {
            $servecss = false;
            $cache->delete('exdate-' . $referrer_host);
        }
    }
}
// commented out for testing
/*
if(! $servecss) {
    header('HTTP/1.0 402 Payment Required');
    exit;
}
*/

// okay, we can fulfill the request.

$requri = $_SERVER['QUERY_STRING'];

if (! is_null($requri)) {
    $reqkey = hash('tiger160,4', $requri, false);
    $cachedFile = $cache->get($reqkey);
    if (! is_null($cachedFile)) {
        if (file_exists($cachedFile)) {
            $obj = new FileWrapper($cachedFile, null, 'text/css', 1209600);
            $obj->sendfile();
            exit();
        }
    }
}
// either no entry in cache or cached file didn't exist







$family = '';

if (isset($_GET['family'])) {
    $family = trim($_GET['family']);
}









$fontFamilies = array();



$biggarray = explode('|', $family);
foreach ($biggarray as $famstring) {
    $arr = explode(':', $famstring);
    $fam = strtolower(trim($arr[0]));
    $fam = preg_replace('/\s+/', '', $fam);
    if (strlen($fam) > 0) {
        if (! in_array($fam, $fontFamilies)) {
            $fontFamilies[] = $fam;
        }
    }
}

sort($fontFamilies);

$latest = 0;
$cssFiles = array();
$rootDir = dirname(__FILE__);

foreach ($fontFamilies as $fam) {
    switch ($fam) {
        case 'librefranklin':
            $file = $rootDir . '/LibreFranklin/webfont.css';
            if (file_exists($file)) {
                $cssFiles[] = $file;
                $ts = filemtime($file);
                if ($ts > $latest) {
                    $latest = $ts;
                }
            }
            break;
        case 'notosans':
            $file = $rootDir . '/NotoSans/webfont.css';
            if (file_exists($file)) {
                $cssFiles[] = $file;
                $ts = filemtime($file);
                if ($ts > $latest) {
                    $latest = $ts;
                }
            }
            break;
    }
}

if ($latest === 0) {
  //404
    exit;
}

$string = implode('|', $fontFamilies) . '_' . $latest;
// this is what we'll cash with a hash of the request uri
$md5 = hash('tiger192,4', $string, false);

/* now serve file if exists, build if doesn't */

$cachedFile = dirname(dirname(__FILE__)) . '/csscache/' . $md5 . '.css';

if (! file_exists($cachedFile)) {
  // okay it didn't exist
    $cssString = '';

    foreach ($cssFiles as $contentFile) {
        $string = trim(file_get_contents($contentFile));
        $cssString = $cssString . $string . "\n\n";
    }

  // todo - minify

  // write to file
    file_put_contents($cachedFile, $string);
  // now it exists
}

// testing testing 123
var_dump($cachedFile);
exit;

$obj = new FileWrapper($cachedFile, null, 'text/css', 1209600);
$obj->sendfile();

if (isset($reqkey)) {
    // cache for 12 hours
    $cache->set($reqkey, $cachedFile, 43200);
}

exit();

?>