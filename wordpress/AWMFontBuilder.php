<?php
declare(strict_types=1);

/**
 * Plugin Name:  AWM Google Font Builder
 * Plugin URI:   https://gist.github.com/AliceWonderMiscreations/b0071e48a27a536142ce38bf6868336b
 * Description:  Provides a class theme developers can use to build a Google
 *               Webfont URI
 * Version:      0.83
 * Requires PHP: 7.0
 * Author:       AliceWonderMiscreations
 * Author URI:   https://notrackers.com/
 * License:      MIT or GPL2
 * License URI:  https://opensource.org/licenses/MIT
 *
 * @package AWonderPHP/WebfontBuilder
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://gist.github.com/AliceWonderMiscreations/b0071e48a27a536142ce38bf6868336b
 */
 
/*
 * License Note:
 *
 * I like MIT license but WordPress likes GPLv2 so whichever floats your boat.
 *
 * Long term desire is for WordPress to have this class available from core.
 * Google Fonts is used by enough themes (like almost every one) that this really
 * should be a core feature.
 *
 * Until then theme authors that want to use this will have to include this
 * themselves in their themes or kindly ask the blogmaster to put this into
 * their must use directory.
 */

/* if modifying class when including in a theme please change the namespace to avoid conflicts */

namespace AWonderPHP\WebfontBuilder;

if (! class_exists('AWMFontBuilder')) {

    /**
     * Builds a webfont url using Google Font specification
     */
    class AWMFontBuilder
    {
        /**
         * @var string The mirror for Google Fonts to use. Set by constructor.
         */
        protected $mirror;

        /**
         * @var array An array containing arguments for the family GET parameter.
         */
        protected $fontArgs = array();
    
        /**
         * @var array An array of language subsets to use.
         */
        protected $subsetArgs = array('latin', 'latin-ext');
        
        /**
         * Converts weights specified by name to their numeric equivalent
         * and returns 400 if it can't figure it out, just to not break things.
         *
         * @param string $weight The weight to convert to an integer.
         *
         * @return int
         */
        protected function weightToNumeric($weight): int
        {
            $weight = trim(strtolower($weight));
            $s = array();
            $r = array();
            $s[] = '/[^a-z]/';
            $r[] = '';
            $s[] = '/italic/';
            $r[] = '';
            $weight = preg_replace($s, $r, $weight);
            switch ($weight) {
                case 'thin':
                    return 100;
                  break;
                case 'extralight':
                    return 200;
                  break;
                case 'light':
                    return 300;
                  break;
                case 'medium':
                    return 500;
                  break;
                case 'semibold':
                    return 600;
                  break;
                case 'bold':
                    return 700;
                  break;
                case 'extrabold':
                    return 800;
                  break;
                case 'book':
                    return 900;
                  break;
                case 'black':
                    return 900;
                  break;
            }
            return 400;
        }//end weightToNumeric()
        
        /**
         * Takes a Google Fonts compatible family string and returns parameters
         * that can be used with the setFont() method.
         *
         * @param string $fontstring The string to parse.
         *
         * @return array An array with three elements corresponding to font family (string),
         *               requested font weights (array), and whether or not italic variant
         *               is requested.
         */
        public static function parseFontParameters(string $fontstring): array
        {
            $params = array();
            $weights = array();
            $italic = false;
            $arr = explode(':', $fontstring);
            $family = $arr[0];
            if (count($arr) > 1) {
                $params = explode(',', $arr[1]);
            }
            foreach ($params as $param) {
              // check for italic
                $param = strtolower($param);
                $test = substr($param, -3);
                if ($test === '00i') {
                    $italic = true;
                    $param = preg_replace('/00i$/', '00', $param);
                }
                if (substr_count($param, 'italic') > 0) {
                    $italic = true;
                    $param = preg_replace('/italic/', '', $param);
                }
                if (is_numeric($param)) {
                    $param = intval($param);
                } else {
                    $param = self::weightToNumeric($param);
                }
                if (! in_array($param, $weights)) {
                    $weights[] = $param;
                }
            }
            // sort the weights
            asort($weights);
            $return = array($family, $weights, $italic);
            return $return;
        }//end parseFontParameters()

        /**
         * Builds a font string.
         *
         * @param string $family  Required. The font family being specified.
         * @param array  $weights Optional. An array of font weights to be used.
         *                        Defaults to an empty array.
         * @param bool   $italic  Optional. Whether or not to request webfont for
         *                        the italic variant. Defaults to true.
         *
         * @return void
         */
        public function setFont(string $family, array $weights = array(), bool $italic = true): void
        {
            $combined = array();
            $safeWeights = array();
            $family = trim($family);
            
            $fontstring = preg_replace('/\s+/', '+', $family);
        
            if (count($weights) > 0) {
                foreach ($weights as $weight) {
                    if (! is_numeric($weight)) {
                        $weight = (string) $weight;
                        $weight = $this->weightToNumeric($weight);
                    }
                    $weight = abs(intval($weight));
                    if (($weight >= 100) && ($weight <= 950)) {
                        if (! in_array($weight, $safeWeights)) {
                            $safeWeights[] = $weight;
                        }
                    }
                }
            }
            asort($safeWeights);
            
            if (count($safeWeights) > 0) {
                foreach ($safeWeights as $weight) {
                    $combined[] = $weight;
                    if ($italic) {
                        $combined[] = $weight . 'i';
                    }
                }
            }
            if (count($combined) === 0) {
                if ($italic) {
                    $fontstring .= ':Italic';
                }
            } else {
                $fontstring = $fontstring . ':' . implode(',', $combined);
            }
            if (strlen($fontstring) > 0) {
                $this->fontArgs[] = $fontstring;
            }
        }//end setFont()

        /**
         * Specifies language subsets. Has to validate them because if
         * a subset has a typo, Google sends a 404 for the CSS even with
         * browsers where they ignore subset and send unicode range.
         *
         * @param array $subsets Required. The language subsets to use.
         *
         * @return void
         */
        public function setSubset(array $subsets): void
        {
            // define the legal subsets
            $legal = array(
                'all',
                'arabic',
                'bengali',
                'cyrillic',
                'cyrillic-ext',
                'devanagari',
                'greek',
                'greek-ext',
                'gujarati',
                'gurmukhi',
                'hebrew',
                'kannada',
                'khmer',
                'korean',
                'malayalam',
                'myanmar',
                'oriya',
                'sinhala',
                'tamil',
                'telugu',
                'thai',
                'vietnamese'
            );
            foreach ($subsets as $subset) {
                $subset = trim(strtolower($subset));
                if (! in_array($subset, $this->subsetArgs)) {
                    if (in_array($subset, $legal)) {
                        $this->subsetArgs[] = $subset;
                    }
                }
            }
        }//end setSubset()

        /**
         * Generates the URL for the webfont CSS file. Should be called by the
         * theme when generating the <head />
         *
         * @param string $name What to use in the first argument of `wp_enqueue_style()`
         *                     which is used when creating the id attribute of the link
         *                     tag.
         *
         * @return void
         */
        public function addWebfontToHead(string $name = ''): void
        {
            if (count($this->fontArgs) === 0) {
                return;
            }
            $name = trim($name);
            if (strlen($name) === 0) {
                // This requires PHP 7, could be done differently
                // for older PHP but PHP 5.6.x is security update
                // only and really shouldn't be used.
                //
                // 64 bits of entropy is plenty to make sure that
                // id collision does not happen.
                $rand = random_bytes(8);
                // remove padding = at end of encoded string
                $string = substr(base64_encode($rand), 0, 11);
                $name = $string . '-fonts';
            }
            $test = substr($name, -6);
            if ($test !== '-fonts') {
                $name .= '-fonts';
            }
            $base = 'https://' . $this->mirror . '/css';
            $queryArgs = array();
            $queryArgs['family'] = implode('|', $this->fontArgs);
            $queryArgs['subset'] = implode(',', $this->subsetArgs);
            // wordpress defined functions
            $url = add_query_arg($queryArgs, $base);
            $url = esc_url($url);
            wp_enqueue_style($name, $url, null, null);
        }//end addWebfontToHead()

        /**
         * The constructor function.
         */
        public function __construct()
        {
            // allows the blog master to define a google fonts API compatible mirror for webfonts
            // in their wp-config.php and have it used
            if (defined('WEBFONT_MIRROR')) {
                $this->mirror = WEBFONT_MIRROR;
            } else {
                $this->mirror = 'fonts.googleapis.com';
                // TODO - add fonts.gstatic.com to dns prefetch
            }
        }//end __construct()
    }//end class

}

?>