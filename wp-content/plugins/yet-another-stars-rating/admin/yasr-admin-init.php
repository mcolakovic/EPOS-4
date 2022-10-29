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

if(!is_admin()) {
    return;
}

//e.g. http://localhost/plugin_development/wp-content/plugins/yet-another-stars-rating/admin/js/
define('YASR_JS_DIR_ADMIN', plugins_url() . '/' . YASR_RELATIVE_PATH_ADMIN . '/js/');

//define gutenberg paths
define('YASR_JS_GUTEN', plugins_url() . '/' . YASR_RELATIVE_PATH_ADMIN . '/js/guten/');
define('YASR_JS_GUTEN_BLOCKS', YASR_JS_GUTEN . 'blocks/');

//CSS directory absolute URL
define('YASR_CSS_DIR_ADMIN', plugins_url() . '/' . YASR_RELATIVE_PATH_ADMIN . '/css/');

//this define must triggered after the active theme's functions.php file is loaded
add_action('init', function (){
    $text = __('This feature is available only in the pro version', 'yet-another-stars-rating');
    $lockImage =
        apply_filters('yasr_feature_locked',
            '<span class="dashicons dashicons-lock" title="'.$text.'"></span>',
            10,
            1);
    define ('YASR_LOCKED_FEATURE', $lockImage);
});


require YASR_ABSOLUTE_PATH_ADMIN . '/yasr-update-functions.php';
require YASR_ABSOLUTE_PATH_ADMIN . '/yasr-admin-functions.php';
require YASR_ABSOLUTE_PATH_ADMIN . '/yasr-admin-actions.php';
require YASR_ABSOLUTE_PATH_ADMIN . '/yasr-admin-actions-ajax.php';
require YASR_ABSOLUTE_PATH_ADMIN . '/yasr-admin-filters.php';
require YASR_ABSOLUTE_PATH_ADMIN . '/settings/yasr-settings-functions-misc.php';

/**
 * Callback function for the spl_autoload_register above.
 *
 * @param $class
 */
function yasr_autoload_admin_classes($class) {
    /**
     * If the class being requested does not start with 'Yasr' prefix,
     * it's not in Yasr Project
     */
    if (0 !== strpos($class, 'Yasr')) {
        return;
    }
    $file_name =  YASR_ABSOLUTE_PATH_ADMIN . '/classes/' . $class . '.php';

    // check if file exists, just to be sure
    if (file_exists($file_name)) {
        require($file_name);
    }

    $file_name_settings = YASR_ABSOLUTE_PATH_ADMIN . '/settings/classes/' . $class . '.php';

    // check if file exists, just to be sure
    if (file_exists($file_name_settings)) {
        require($file_name_settings);
    }

    $file_name_editor = YASR_ABSOLUTE_PATH_ADMIN . '/editor/' . $class . '.php';

    // check if file exists, just to be sure
    if (file_exists($file_name_editor)) {
        require($file_name_editor);
    }

}

//AutoLoad Yasr Shortcode Classes, only when a object is created
spl_autoload_register('yasr_autoload_admin_classes');

$yasr_settings = new YasrSettings();
$yasr_settings->init();

$yasr_editor  = new YasrEditorHooks();
$yasr_editor->init();