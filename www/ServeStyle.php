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

use \AWonderPHP\FileWrapper\FileWrapper as FileWrapper;

/*
 * TODO - use a hash of the request uri as a key to see if matching cached CSS already is set
 *        and only calculate the matching CSS if it isn't set or cached CSS doesn't exist.
 *
 *        Use redis for this, not APCu, so it persists a server restart.
 */

$family = '';

if(isset($_GET['family'])) {
    $family = trim('family');
}

$fontFamilies = array();



$biggarray = explode('|', $family);
foreach($biggarray as $famstring) {
  $arr = explode(':', $famstring);
  $fam = strtolower(trim($arr[0]));
  if(strlen($fam) > 0) {
    if(! in_array($fam, $fontFamilies)) {
      $fontFamilies[] = $fam;
    }
  }
}

sort($fontFamilies);
$latest = 0;
$cssFiles = array();
$rootDir = dirname(__FILE__);

foreach($fontFamilies as $fam) {
  switch($fam) {
    case 'librefranklin':
      $file = $rootDir . '/LibreFranklin/webfont.css';
      if(file_exists($file)) {
        $cssFiles[] = $file;
        $ts = filemtime($file);
        if($ts > $latest) {
          $latest = $ts;
        }
      }
      break;
    case 'notosans':
      if(file_exists($file)) {
        $file = $rootDir . '/LibreFranklin/webfont.css';
        $cssFiles[] = $file;
        $ts = filemtime($file);
        if($ts > $latest) {
          $latest = $ts;
        }
      }
      break;
  }
}

if($latest === 0) {
  //404
  exit;
}

$string = implode('|', $fontFamilies) . '_' . $latest;
// this is what we'll cash with a hash of the request uri
$md5 = md5sum($string);

/* now serve file if exists, build if doesn't */

$cachedFile = dirname(dirname(__FILE__)) . '/csscache/' . $md5 . 'css';
if(! file_exists($cachedFile)) {
  // okay it didn't exist
  $cssString = '';

  foreach($cssFiles as $contentFile) {
    $string = trim(file_get_contents($contentFile));
    $cssString = $cssString . $string . "\n\n";
  }

  // todo - minify

  // write to file
  file_put_contents($cachedFile, $string);
  // now it exists
}

$obj = new FileWrapper($cachedFile, null, 'text/css', 1209600);
$obj->sendfile();
exit();

?>