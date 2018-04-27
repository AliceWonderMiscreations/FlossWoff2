<?php
declare(strict_types = 1);

/**
 * Alternative to Google Fonts.
 *
 * This file finds and serves the appropriate CSS based upon the GET arguments
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

$family = '';

if(isset($_GET['family'])) {
    $family = trim('family');
}

$s = array();
$r = array();

$s[] = '/\+/';
$r[] = '';

$family = preg_replace($s, $r, $family);

switch($family) {
    case 'LibreFranklin':
        $file = dirname(__FILE__) . '/LibreFranklin/webfont.css';
        if(file_exists($file)) {
            $css = $file;
        }
    case 'NotoSans':
        $file = dirname(__FILE__) . '/NotoSans/webfont.css';
        if(file_exists($file)) {
            $css = $file;
        }
    break;
}

if(! isset($css)) {
    //send a 404 but just exit for now
    exit;
}

$obj = new FileWrapper($css, null, 'text/css', 1209600);
$obj->sendfile();

exit();

?>