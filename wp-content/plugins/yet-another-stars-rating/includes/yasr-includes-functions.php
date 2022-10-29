<?php

/*

Copyright 2014 Dario Curvino (email : d.curvino@tiscali.it)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

/***** Adding global js and css needed on both admin and public *****/
add_action('wp_enqueue_scripts',    'yasr_add_scripts_includes', 11);
add_action('admin_enqueue_scripts', 'yasr_add_scripts_includes', 11);

function yasr_add_scripts_includes() {
    //This is required to use wp_add_inline_script without dependency
    //https://wordpress.stackexchange.com/a/311279/48442
    wp_register_script( 'yasr-global-data', '', [], '', true );
    wp_enqueue_script( 'yasr-global-data' );

    $yasr_visitor_votes_loader = '<div id="loader-visitor-rating" style="display: inline-block">&nbsp; '.
        ' <img src="' . esc_url(YASR_IMG_DIR . 'loader.gif').'" title="yasr-loader" alt="yasr-loader"></div>';

    $yasr_common_data = json_encode(array(
        'siteUrl'             => site_url(),
        'ajaxurl'             => admin_url('admin-ajax.php'),
        'visitorStatsEnabled' => YASR_VISITORS_STATS,
        'ajaxEnabled'         => YASR_ENABLE_AJAX,
        'loaderHtml'          => $yasr_visitor_votes_loader,
    ));

    //check if wp_add_inline_script has already run before
    if(!defined('YASR_GLOBAL_DATA_EXISTS')) {
        wp_add_inline_script(
            'yasr-global-data', 'var yasrCommonData = ' . $yasr_common_data, 'before'
        );

        //use a constant to be sure that yasr-global-data is not loaded twice
        define ('YASR_GLOBAL_DATA_EXISTS', true);
    }

    $yasr_multiset_theme_handle = 'yasrcsslightscheme';
    $yasr_multiset_theme = 'yasr-table-light.css';

    //default css is the light one
    if (YASR_SCHEME_COLOR === 'dark') {
        $yasr_multiset_theme_handle = 'yasrcssdarkscheme';
        $yasr_multiset_theme = 'yasr-table-dark.css';
    }

    wp_enqueue_style(
        $yasr_multiset_theme_handle,
        YASR_CSS_DIR_INCLUDES . $yasr_multiset_theme,
        '',
        YASR_VERSION_NUM
    );

}

/*** Css rules for stars set, from version 1.2.7
 * Here I use add_action instead of directly use wp_add_inline_style so I can
 * use remove_action if needed (e.g. Yasr Stylish)
 ***/
add_action('yasr_add_front_script_css', 'yasr_css_stars_set');
add_action('yasr_add_admin_scripts_end', 'yasr_css_stars_set');

function yasr_css_stars_set() {

    //if star selected is "rater", select the images
    if (YASR_STARS_SET === 'rater') {
        $star_grey   = YASR_IMG_DIR . 'star_0.svg';
        $star_yellow = YASR_IMG_DIR . 'star_1.svg';
    } elseif (YASR_STARS_SET === 'rater-oxy') {
        $star_grey   = YASR_IMG_DIR . 'star_oxy_0.svg';
        $star_yellow = YASR_IMG_DIR . 'star_oxy_1.svg';
    } //by default, use the one provided by Yasr
    else {
        $star_grey   = YASR_IMG_DIR . 'star_2.svg';
        $star_yellow = YASR_IMG_DIR . 'star_3.svg';
    }


    $yasr_st_css = "
        .yasr-star-rating {
            background-image: url(\"$star_grey\");
        }
        .yasr-star-rating .yasr-star-value {
            background: url(\"$star_yellow\") ;
        }
    ";

    wp_add_inline_style('yasrcss', $yasr_st_css);

}

add_action('yasr_add_front_script_css', 'yasr_rtl_support');
add_action('yasr_add_admin_scripts_end', 'yasr_rtl_support');

function yasr_rtl_support() {
    if (is_rtl()) {
        $yasr_rtl_css = '.yasr-star-rating .yasr-star-value {
                        -moz-transform: scaleX(-1);
                        -o-transform: scaleX(-1);
                    
                        -webkit-transform: scaleX(-1);
                        transform: scaleX(-1);
                        filter: FlipH;
                        -ms-filter: "FlipH";
                        right: 0;
                        left: auto;
                    }';

        wp_add_inline_style('yasrcss', $yasr_rtl_css);
    }
}

/**
 * This function enqueue the js scripts required on both admin and frontend
 *
 * @author Dario Curvino <@dudo>
 * @since 2.8.5
 */

function yasr_enqueue_includes_js_scripts() {
    wp_enqueue_script('jquery');

    wp_enqueue_script(
        'rater',
        YASR_JS_DIR_INCLUDES .
        'rater-js.min.js',
        '',
        YASR_VERSION_NUM,
        true
    );

    if(defined('YASR_CATCH_INFINITE_SCROLL_INSTALLED') && YASR_CATCH_INFINITE_SCROLL_INSTALLED === true) {
        $array_dep = array('jquery', 'rater', 'wp-i18n', 'yasr-global-data', 'wp-element');
        //laod tippy only if the shortcode has loaded it
        if(YasrVisitorVotes::tippyLoaded() === true) {
            $array_dep[] = 'tippy';
        }
        wp_enqueue_script(
            'yasr_catch_infinite',
            YASR_JS_DIR_INCLUDES .
            'catch-inifite-scroll.js',
            $array_dep,
            YASR_VERSION_NUM,
            true
        );
    }

}

/****** Translating YASR ******/
add_action('init', 'yasr_translate');

function yasr_translate() {
    load_plugin_textdomain('yet-another-stars-rating', false, YASR_LANG_DIR);
}

/**
 * Create a select menu to choose the rich snippet itemtype
 *
 * @param bool|string $html_id the id of the select name
 * @param bool|int $term_id
 * @param bool $disabled
 */

function yasr_select_itemtype($html_id=false, $term_id=false, $disabled=false) {
    if($html_id === false) {
        $html_id = 'yasr-choose-reviews-types-list';
    }
    $itemtypes_array  = json_decode(YASR_SUPPORTED_SCHEMA_TYPES);

    sort($itemtypes_array);

    $review_type_choosen = yasr_get_itemType($term_id);

    $disabled_attribute = '';

    if($disabled === true) {
        $disabled_attribute = 'disabled';
    }
    ?>

    <label for="<?php echo esc_attr($html_id) ?>"></label>
    <select name="yasr-review-type" id="<?php echo esc_attr($html_id) ?>">
        <?php
        foreach ($itemtypes_array as $itemType) {
            $itemType = trim($itemType);
            if ($itemType === $review_type_choosen) {
                echo "<option value='".esc_attr($itemType)."' selected >
                          ".esc_html($itemType)."
                      </option>";
            } else {
                echo "<option value='".esc_attr($itemType)."' ".esc_attr($disabled_attribute).">
                        ".esc_html($itemType)."
                      </option>";
            }
        }
        ?>
    </select>

    <?php
} //End function yasr_select_itemtype()

/*** Function to set cookie
 * @since 0.8.3
 *
 * @param $cookiename //can comes from a filter
 * @param $data_to_save
 */
function yasr_setcookie($cookiename, $data_to_save) {

    if (!$data_to_save || !$cookiename || !is_string($cookiename)) {
        exit('Error setting yasr cookie');
    }

    //sanitize the cookie name
    $cookiename = wp_strip_all_tags($cookiename);
    $domain = COOKIE_DOMAIN;

    //this is for multisite support
    if(defined('DOMAIN_CURRENT_SITE')) {
        $domain = DOMAIN_CURRENT_SITE;
    }

    $existing_data = array(); //avoid undefined index

    if (isset($_COOKIE[$cookiename])) {
        //setcookie add \ , so I need to stripslahes
        $existing_data = stripslashes($_COOKIE[$cookiename]);

        //By default, json_decode return an object, TRUE to return an array
        $existing_data = json_decode($existing_data, true);
    }

    //whetever exists or not, push into at the end of array
    $existing_data[] = $data_to_save;

    $encoded_data = json_encode($existing_data);
    $expire = time() + 31536000;

    if (PHP_VERSION_ID < 70300) {
        setcookie($cookiename, $encoded_data, $expire, COOKIEPATH . '; samesite=' . 'Lax', $domain, false);
        return;
    }
    setcookie($cookiename, $encoded_data, [
        'expires'  => $expire,
        'path'     => COOKIEPATH,
        'domain'   => $domain,
        'samesite' => 'Lax',
        'secure'   => false,
        'httponly' => false,
    ]);

}

/*** Function to get ip, since version 0.8.8
 * This code can be found on http://codex.wordpress.org/Plugin_API/Filter_Reference/pre_comment_user_ip
 ***/

function yasr_get_ip() {

    if (YASR_ENABLE_IP === 'yes') {
        $ip = null;
        $ip = apply_filters('yasr_filter_ip', $ip);

        if (isset($ip)) {
            return $ip;
        }
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

        if (!empty($_SERVER['X_FORWARDED_FOR'])) {
            $X_FORWARDED_FOR = explode(',', $_SERVER['X_FORWARDED_FOR']);
            if (!empty($X_FORWARDED_FOR)) {
                $REMOTE_ADDR = trim($X_FORWARDED_FOR[0]);
            }
        }
        /*
        * Some php environments will use the $_SERVER['HTTP_X_FORWARDED_FOR']
        * variable to capture visitor address information.
        */

        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $HTTP_X_FORWARDED_FOR = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if (!empty($HTTP_X_FORWARDED_FOR)) {
                $REMOTE_ADDR = trim($HTTP_X_FORWARDED_FOR[0]);
            }
        }
        return preg_replace('/[^0-9a-f:., ]/si', '', $REMOTE_ADDR);
    }
    return ('X.X.X.X');
}


/*function to remove duplicate in an array for a specific key
Taken value: array to search, key
*/
function yasr_unique_multidim_array($array, $key) {

    $temp_array = array();
    $i          = 0;

    //creo un array vuoto che conterrà solo gli indici
    $key_array = array();

    foreach ($array as $val) {
        $result_search_array = array_search($val[$key], $key_array);

        $key_array[$i]  = $val[$key];
        $temp_array[$i] = $val;

        //if result is found
        if ($result_search_array !== false) {
            unset($key_array[$result_search_array], $temp_array[$result_search_array]);
        }
        $i ++;
    }
    sort($temp_array);
    return $temp_array;
}

function yasr_check_valid_url($url) {

    $timeout = 5;

    //Check if url is valid
    if (filter_var($url, FILTER_VALIDATE_URL) !== false) {

        //Check if curl is installed
        if (function_exists('curl_version')) {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

            //execute curl
            $http_respond = trim(strip_tags(curl_exec($ch)));
            $http_code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            //Check the response
            if (($http_code == '200') || ($http_code == '302')) {
                return true;
            }

            return false;
            //close curl
            curl_close($ch);

        } //if curl is not installed, use file_get_contents
        else {
            //...but only if enabled on the server!
            if (ini_get('allow_url_fopen')) {

                //Change timeout for file_get_contents
                ini_set('default_socket_timeout', $timeout); //5 seconds

                $headers         = get_headers($url, 1);
                $string_to_check = '200 OK';

                //check if in the first heade we've 200 OK
                if (strpos($headers[0], $string_to_check) !== false) {
                    return true;
                }

                return false;
            } //if url_fopen is not enabled

            return false;

        }
    } else {
        return false;
    }

}


/**
 * Check if the given string is a supported itemType
 *
 * @param string $item_type
 * @return bool
 *
 * @since 2.1.5
 */

function yasr_is_supported_schema ($item_type) {
    $supported_schema_array = json_decode(YASR_SUPPORTED_SCHEMA_TYPES);

    if (in_array($item_type, $supported_schema_array)) {
        return true;
    }

    return false;
}

/**
 * @author Dario Curvino <@dudo>
 * @since 2.9.3
 * @return bool
 */
function yasr_is_catch_infinite_sroll_installed () {
    if (is_plugin_active('catch-infinite-scroll/catch-infinite-scroll.php')) {
        return true;
    }
    return false;
}