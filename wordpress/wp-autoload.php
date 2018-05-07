<?php
/**
 * Plugin Name:  PSR-4 Autoloader
 * Plugin URI:   https://gist.github.com/AliceWonderMiscreations/4ba7209256f0e2b38d59a8787d164f63
 * Description:  Provides PSR-4 autoloading
 * Version:      0.1
 * Requires PHP: 5.6
 * Author:       AliceWonderMiscreations
 * Author URI:   https://notrackers.com/
 * License:      CC0
 * License URI:  https://creativecommons.org/choose/zero/
 *
 * @package AWonderPHP/WPAutoloader
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://creativecommons.org/choose/zero/ MIT
 * @link    https://gist.github.com/AliceWonderMiscreations/4ba7209256f0e2b38d59a8787d164f63
 */

// this could be custom defined in wp-config.php
if (! defined('WP_PSR4')) {
    define('WP_PSR4', WP_CONTENT_DIR . '/wp-psr4');
}

if (! class_exists('WordPressAutoload')) {
    /**
     * Class of static methods (er method) for autoloading other PHP class files.
     * TODO - add a static method for loading PEAR modules.
     *
     * This breaks PSR2 because it is not namespaced in its own file that matches
     * the class name but is mixed with code that calls it. That is necessary for
     * it to be in a single file plugin for the mu-plugins directory.
     *
     * WordPress is not exactly a PSR-2 compliant project...
     */
    class WordPressAutoload
    {
        /**
         * @var array Array of allowed class file suffixes
         */
        const SUFFIXARRAY = array('.php', '.class.php', '.inc.php');

        /**
         * Looks for a class and loads it if found.
         *
         * For classes with just a vendor namespace and no product namespace,
         * require they be installed as Vendor/ClassName/ClassName.suffix.
         *
         * @param string $class The class to be loaded.
         *
         * @return void
         */
        public static function loadClass(string $class)
        {
            $arr = explode("\\", $class);
            $j = count($arr);
            if ($j < 3) {
                if ($j === 2) {
                    $arr[] = $arr[1];
                } else {
                    return;
                }
            }
            $pathNoSuffix = WP_PSR4 . '/' . implode('/', $arr);
            foreach (self::SUFFIXARRAY as $suffix) {
                $path = $pathNoSuffix . $suffix;
                if (file_exists($path)) {
                    require_once($path);
                    return;
                }
            }
        }//end loadClass
    }//end WordPressAutoload

    spl_autoload_register(function ($class) {
        WordPressAutoload::loadClass($class);
    });
}

?>