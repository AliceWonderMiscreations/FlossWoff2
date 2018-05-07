#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**
 * Utility to remove potentially crusty filesystem cached CSS files.
 * This utility needs to be run with the permissions of the web server.
 *
 * su -l apache -s /bin/bash --command="/usr/bin/php /full/path/to/fileCacheMaintenance.php"
 *
 * @package AWonderPHP/FlossWoff2
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/FlossWoff2
 */

$uid = posix_getuid();
if ($uid === 0) {
    echo "Do not run this as r00t, you t00l!\n";
    exit(1);
}

$now = time();
// this staggers things so massive deleting does not happen of potentially
// good files
$max = $now - (90 * 24 * 3600);
$min = $now - (210 * 24 * 3600);

$cacheDir = dirname(dirname(__FILE__)) . '/csscache';

if (! file_exists($cacheDir)) {
    echo "I can not find your csscache file directory.\n";
    exit(1);
}

$fileArray = scandir($cacheDir);

foreach ($fileArray as $file) {
    $test = substr($file, -4);
    if ($test === ".css") {
        $triggerAge = random_int($min, $max);
        if (! $tstamp = fileatime($cacheDir . '/' . $file)) {
            if (! $tstamp = filemtime($cacheDir . '/' . $file)) {
                $tstamp = null;
            }
        }
        if (! is_null($tstamp)) {
            if ($tstamp < $triggerAge) {
                if (! unlink($cacheDir . '/' . $file)) {
                    echo "Could not link file. Are you running this as the web server daemon user?\n";
                    echo "You should be. Exiting now,\n";
                    exit(1);
                }
            }
        }
    }
}
exit;

?>