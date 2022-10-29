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

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'You\'re not allowed to see this page' );
} // Exit if accessed directly

//e.g. http://localhost/plugin_development/wp-content/plugins/yet-another-stars-rating/includes/js/
define('YASR_JS_DIR_INCLUDES', plugins_url() . '/' . YASR_RELATIVE_PATH_INCLUDES . '/js/');
//CSS directory absolute URL
define('YASR_CSS_DIR_INCLUDES', plugins_url() . '/' . YASR_RELATIVE_PATH_INCLUDES . '/css/');

global $wpdb;
//defining tables names
define('YASR_LOG_TABLE', $wpdb->prefix . 'yasr_log');
define('YASR_LOG_MULTI_SET', $wpdb->prefix . 'yasr_log_multi_set');
define('YASR_MULTI_SET_NAME_TABLE', $wpdb->prefix . 'yasr_multi_set');
define('YASR_MULTI_SET_FIELDS_TABLE', $wpdb->prefix . 'yasr_multi_set_fields');

require YASR_ABSOLUTE_PATH_INCLUDES . '/yasr-includes-functions.php';
require YASR_ABSOLUTE_PATH_INCLUDES . '/yasr-includes-db-functions.php';
require YASR_ABSOLUTE_PATH_INCLUDES . '/yasr-widgets.php';


/**
 * Callback function for the spl_autoload_register above.
 *
 * @param $class
 */
function yasr_autoload_includes_classes($class) {
    /**
     * If the class being requested does not start with 'Yasr' prefix,
     * it's not in Yasr Project
     */
    if (0 !== strpos($class, 'Yasr')) {
        return;
    }
    $file_name =  YASR_ABSOLUTE_PATH_INCLUDES . '/classes/' . $class . '.php';

    // check if file exists, just to be sure
    if (file_exists($file_name)) {
        require($file_name);
    }

}

//AutoLoad Yasr Classes, only when a object is created
spl_autoload_register('yasr_autoload_includes_classes');

require YASR_ABSOLUTE_PATH_INCLUDES . '/shortcodes/yasr-shortcode-functions.php';


/****** Getting options ******/
//Get general options
$yasr_stored_options = get_option('yasr_general_options');

global $yasr_stored_options;

if(isset($yasr_stored_options['auto_insert_enabled'])) {
    define('YASR_AUTO_INSERT_ENABLED', (int)$yasr_stored_options['auto_insert_enabled']);
} else {
    define('YASR_AUTO_INSERT_ENABLED', null);
}

if (YASR_AUTO_INSERT_ENABLED === 1) {
    define('YASR_AUTO_INSERT_WHAT', $yasr_stored_options['auto_insert_what']);
    define('YASR_AUTO_INSERT_WHERE', $yasr_stored_options['auto_insert_where']);
    define('YASR_AUTO_INSERT_ALIGN', $yasr_stored_options['auto_insert_align']);
    define('YASR_AUTO_INSERT_SIZE', $yasr_stored_options['auto_insert_size']);
    define('YASR_AUTO_INSERT_EXCLUDE_PAGES', $yasr_stored_options['auto_insert_exclude_pages']);
    define('YASR_AUTO_INSERT_CUSTOM_POST_ONLY', $yasr_stored_options['auto_insert_custom_post_only']);
}  else {
    define('YASR_AUTO_INSERT_WHAT', null);
    define('YASR_AUTO_INSERT_WHERE', null);
    define('YASR_AUTO_INSERT_ALIGN', null);
    define('YASR_AUTO_INSERT_SIZE', null);
    define('YASR_AUTO_INSERT_EXCLUDE_PAGES', null);
    define('YASR_AUTO_INSERT_CUSTOM_POST_ONLY', null);
}

if(isset($yasr_stored_options['stars_title'])) {
    define('YASR_STARS_TITLE', $yasr_stored_options['stars_title']);
} else {
    define('YASR_STARS_TITLE', null);
}

if (YASR_STARS_TITLE === 'yes') {
    define('YASR_STARS_TITLE_WHAT', $yasr_stored_options['stars_title_what']);
    define('YASR_STARS_TITLE_EXCLUDE_PAGES', $yasr_stored_options['stars_title_exclude_pages']);
    define('YASR_STARS_TITLE_WHERE', $yasr_stored_options['stars_title_where']);
} else {
    define('YASR_STARS_TITLE_WHAT', null);
    define('YASR_STARS_TITLE_EXCLUDE_PAGES', null);
    define('YASR_STARS_TITLE_WHERE', null);
}

if(isset($yasr_stored_options['show_overall_in_loop'])) {
    define('YASR_SHOW_OVERALL_IN_LOOP', $yasr_stored_options['show_overall_in_loop']);
} else {
    define('YASR_SHOW_OVERALL_IN_LOOP', null);
}

if(isset($yasr_stored_options['show_visitor_votes_in_loop'])) {
    define('YASR_SHOW_VISITOR_VOTES_IN_LOOP', $yasr_stored_options['show_visitor_votes_in_loop']);
} else {
    define('YASR_SHOW_VISITOR_VOTES_IN_LOOP', null);
}

if(isset($yasr_stored_options['visitors_stats'])) {
    define('YASR_VISITORS_STATS', $yasr_stored_options['visitors_stats']);
} else {
    define('YASR_VISITORS_STATS', false);
}

if(isset($yasr_stored_options['allowed_user'])) {
    define('YASR_ALLOWED_USER', $yasr_stored_options['allowed_user']);
} else {
    define('YASR_ALLOWED_USER', false);
}

if(isset($yasr_stored_options['enable_ip'])) {
    define('YASR_ENABLE_IP', $yasr_stored_options['enable_ip']);
} else {
    define('YASR_ENABLE_IP', false);
}

if(isset($yasr_stored_options['snippet_itemtype'])) {
    define('YASR_ITEMTYPE', $yasr_stored_options['snippet_itemtype']);
} else {
    define('YASR_ITEMTYPE', false);
}

if (isset($yasr_stored_options['publisher'])) {
    define('YASR_PUBLISHER_TYPE', $yasr_stored_options['publisher']);
} else {
    define('YASR_PUBLISHER_TYPE', 'Organization'); //default value
}

if (isset($yasr_stored_options['publisher_name'])) {
    define('YASR_PUBLISHER_NAME', $yasr_stored_options['publisher_name']);
} else {
    define('YASR_PUBLISHER_NAME', get_bloginfo('name'));
}

if (isset($yasr_stored_options['publisher_logo'])
    && (filter_var($yasr_stored_options['publisher_logo'], FILTER_VALIDATE_URL) !== false)) {
    define('YASR_PUBLISHER_LOGO', $yasr_stored_options['publisher_logo']);
} else {
    define('YASR_PUBLISHER_LOGO', get_site_icon_url());
}

if (isset($yasr_stored_options['enable_ajax'])) {
    define('YASR_ENABLE_AJAX', $yasr_stored_options['enable_ajax']);
} else {
    define('YASR_ENABLE_AJAX', 'no'); //default value
}

//Get stored style options
$style_options = get_option('yasr_style_options');

global $style_options;

if ($style_options) {
    if (isset($style_options['textarea'])) {
        define('YASR_CUSTOM_CSS_RULES', $style_options['textarea']);
    } else {
        define('YASR_CUSTOM_CSS_RULES', null);
    }

    if (isset($style_options['scheme_color_multiset'])) {
        define('YASR_SCHEME_COLOR', $style_options['scheme_color_multiset']);
    } else {
        define('YASR_SCHEME_COLOR', null);
    }

    if (isset($style_options['stars_set_free'])) {
        define('YASR_STARS_SET', $style_options['stars_set_free']);
    } else {
        define('YASR_STARS_SET', null);
    }

} else {
    define('YASR_CUSTOM_CSS_RULES', null);
    define('YASR_SCHEME_COLOR', null);
    define('YASR_STARS_SET', null);
}

//Multi set options
$multi_set_options = get_option('yasr_multiset_options');
if ($multi_set_options) {
    //Multi set options
    $first_multiset = YasrMultiSetData::returnFirstSetId();
    define('YASR_FIRST_SETID', $first_multiset);

    if(YASR_FIRST_SETID !== false) {
        if (isset($multi_set_options['show_average'])) {
            define('YASR_MULTI_SHOW_AVERAGE', $multi_set_options['show_average']);
        }
        else {
            define('YASR_MULTI_SHOW_AVERAGE', 'yes');
        }
    }
} else {
    define('YASR_FIRST_SETID', 1);
}
/****** End Getting options ******/

define('YASR_LOADER_IMAGE', YASR_IMG_DIR . '/loader.gif');

//Text for button in settings pages
$save_settings_text = __('Save All Settings', 'yet-another-stars-rating');
define('YASR_SAVE_All_SETTINGS_TEXT', $save_settings_text);

//use json_decode for compatibility with php <7
//https://wordpress.org/support/topic/error-after-update-to-version-2-0-6/
$supported_schema_types = json_encode(
    array (
        'BlogPosting',
        'Book',
        'Course',
        'CreativeWorkSeason',
        'CreativeWorkSeries',
        'Episode',
        'Event',
        'Game',
        'LocalBusiness',
        'MediaObject',
        'Movie',
        'MusicPlaylist',
        'MusicRecording',
        'Organization',
        'Product',
        'Recipe',
        'SoftwareApplication'
    )
);
$array_item_type_info = json_encode(
    array(
        'yasr_schema_title',
        'yasr_book_author',
        'yasr_book_bookedition',
        'yasr_book_bookformat',
        'yasr_book_isbn',
        'yasr_book_number_of_pages',
        'yasr_localbusiness_address',
        'yasr_localbusiness_pricerange',
        'yasr_localbusiness_telephone',
        'yasr_movie_actor',
        'yasr_movie_datecreated',
        'yasr_movie_director',
        'yasr_movie_duration',
        'yasr_product_brand',
        'yasr_product_global_identifier_select',
        'yasr_product_global_identifier_value',
        'yasr_product_price',
        'yasr_product_price_availability',
        'yasr_product_price_currency',
        'yasr_product_price_url',
        'yasr_product_price_valid_until',
        'yasr_product_sku',
        'yasr_recipe_cooktime',
        'yasr_recipe_description',
        'yasr_recipe_keywords',
        'yasr_recipe_nutrition',
        'yasr_recipe_preptime',
        'yasr_recipe_recipecategory',
        'yasr_recipe_recipecuisine',
        'yasr_recipe_recipeingredient',
        'yasr_recipe_recipeinstructions',
        'yasr_recipe_video',
        'yasr_software_application',
        'yasr_software_os',
        'yasr_software_price',
        'yasr_software_price_availability',
        'yasr_software_price_currency',
        'yasr_software_price_url',
        'yasr_software_price_valid_until',
    )
);

define('YASR_SUPPORTED_SCHEMA_TYPES', $supported_schema_types);
define('YASR_SUPPORTED_SCHEMA_TYPES_ADDITIONAL_FIELDS', $array_item_type_info);

//run includes filters
$yasr_includes_filter = new YasrIncludesFilters();
$yasr_includes_filter->filterCustomTexts($yasr_stored_options);

//support for caching plugins
$yasr_includes_filter->cachingPluginSupport();

$init_ajax = new YasrShortcodesAjax();
$init_ajax->init();

add_action('plugins_loaded', static function () {
   define ('YASR_CATCH_INFINITE_SCROLL_INSTALLED', yasr_is_catch_infinite_sroll_installed());
});

//Load rest API
require YASR_ABSOLUTE_PATH_INCLUDES . '/rest/yasr-rest.php';