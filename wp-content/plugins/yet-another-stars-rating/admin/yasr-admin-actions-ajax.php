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

//ajax action
//This function is also hooked into yasr-admin-actions
add_action('wp_ajax_yasr_change_log_page', 'yasr_widget_log_dashboard_callback');
function yasr_widget_log_dashboard_callback() {
    $log_widget = new YasrLogDashboardWidget();
    $log_widget->adminWidget();
} //End callback function


//ajax action
//This function is also hooked into yasr-admin-actions
add_action('wp_ajax_yasr_change_user_log_page', 'yasr_users_dashboard_widget_callback');
function yasr_users_dashboard_widget_callback() {
    $log_widget = new YasrLogDashboardWidget();
    $log_widget->userWidget();
} //End callback function

//Action to print the shortcode in "Ranking tab"
add_action('wp_ajax_yasr_rankings_preview_shortcode', 'yasr_rankings_preview_shortcode');
/**
 * @author Dario Curvino <@dudo>
 * @since  2.6.3
 */
function yasr_rankings_preview_shortcode() {
    if(!isset($_GET['shortcode']) || !isset($_GET['full_shortcode'])) {
        die();
    }

    $shortcode = $_GET['shortcode'];

    if(!shortcode_exists($shortcode)) {
        die(json_encode(__('This shortcode was not found.', 'yet-another-stars-rating')));
    }

    $full_shortcode = stripslashes($_GET['full_shortcode']);

    echo json_encode(do_shortcode($full_shortcode));

    die();
}