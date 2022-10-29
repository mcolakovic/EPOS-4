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

add_action( 'wp_ajax_yasr_import_wppr', 'yasr_import_wppr_callback' );

function yasr_import_wppr_callback() {

    if($_POST['nonce']) {
        $nonce = $_POST['nonce'];
    } else {
        exit();
    }

    if (!wp_verify_nonce( $nonce, 'yasr-import-wppr-action' ) ) {
        die('Error while checking nonce');
    }

    if (!current_user_can( 'manage_options' ) ) {
        die(esc_html__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
    }

    global $wpdb;

    //get logs
    //With Wp Post Rating I need to import postmeta.
    //It has his own table too, but can be disabled in the settings.
    //The only way to be sure is get the postmeta

    $wppr = new YasrImportRatingPlugins();

    $logs = $wppr->yasr_return_wppr_data();

    if(empty($logs)) {
        echo json_encode(esc_html__('No WP Post Rating data found'));
    } else {
        $result = false;

        /****** Insert logs ******/
        foreach ($logs as $column) {

            if($column->ratings_average > 5) {
                $column->ratings_average = 5;
            }

            for ($i=1; $i<=$column->ratings_users; $i++) {

                //check if rating_average is not null.
                //I found out that sometimes Wp Post Rating can save value with null data (sigh!!)
                if ($column->ratings_average !== null) {

                    $result = $wpdb->replace(
                        YASR_LOG_TABLE,
                        array(
                            'post_id'      => $column->post_id,
                            'user_id'      => 0, //not stored on wp post rating
                            'vote'         => $column->ratings_average,
                            'date'         => 'wppostrating', //not stored on wp post rating
                            'ip'           => 'wppostrating'//not stored on wp post rating
                        ),
                        array('%d', '%d', '%f', '%s', '%s')
                    );
                }
            }
        }

        if ($result) {
            yasr_save_option_imported_plugin('wppr');

            $string_to_return = esc_html__('Woot! All data have been imported!', 'yet-another-stars-rating');
            echo json_encode($string_to_return);
        }

    }
    die();
}

add_action( 'wp_ajax_yasr_import_kksr', 'yasr_import_kksr_callback' );

function yasr_import_kksr_callback() {

    if($_POST['nonce']) {
        $nonce = $_POST['nonce'];
    } else {
        exit();
    }

    if (!wp_verify_nonce( $nonce, 'yasr-import-kksr-action' ) ) {
        die('Error while checking nonce');
    }

    if (!current_user_can( 'manage_options' ) ) {
        die(esc_html__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
    }

    global $wpdb;

    //get logs
    //With KK star rating I need to import postmeta.
    $kksr = new YasrImportRatingPlugins();

    $logs= $kksr->yasr_return_kksr_data();

    if(empty($logs)) {
        echo json_encode(esc_html__('No KK Star Ratings data found'));
    } else {
        $result = false;

        /****** Insert logs ******/
        foreach ($logs as $column) {
            if($column->ratings_average > 5) {
                $column->ratings_average = 5;
            }

            for ($i=1; $i<=$column->ratings_users; $i++) {
                $result = $wpdb->replace(
                    YASR_LOG_TABLE,
                    array(
                        'post_id'      => $column->post_id,
                        'user_id'      => 0, //not stored on KK star rating
                        'vote'         => $column->ratings_average,
                        'date'         => 'kkstarratings', //not stored KK star rating
                        'ip'           => 'kkstarratings'//not stored KK star rating
                    ),
                    array('%d', '%d', '%f', '%s', '%s')
                );
            }
        }

        if ($result) {
            yasr_save_option_imported_plugin('kksr');

            $string_to_return = esc_html__('Woot! All data have been imported!', 'yet-another-stars-rating');
            echo json_encode($string_to_return);
        }

    }
    die();
}

add_action( 'wp_ajax_yasr_import_ratemypost', 'yasr_import_ratemypost_callback' );

function yasr_import_ratemypost_callback() {

    if($_POST['nonce']) {
        $nonce = $_POST['nonce'];
    } else {
        exit();
    }

    if (!wp_verify_nonce($nonce, 'yasr-import-ratemypost-action')) {
        die('Error while checking nonce');
    }

    if (!current_user_can( 'manage_options' ) ) {
        die(esc_html__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
    }

    global $wpdb;

    $rmp = new YasrImportRatingPlugins();

    //get logs
    $logs=$rmp->yasr_return_rmp_data();

    if(empty($logs)) {
        echo json_encode(esc_html__('No Rate My Post data found'));
    } else {
        $result = false;

        /****** Insert logs ******/
        foreach ($logs as $column) {
            $result = $wpdb->replace(
                YASR_LOG_TABLE,
                array(
                    'post_id'      => $column->post_id,
                    'user_id'      => 0, //seems like rate my post store all users like -1, so I cant import the user_id
                    'vote'         => $column->vote,
                    'date'         => $column->date,
                    'ip'           => 'ratemypost'
                ),
                array('%d', '%d', '%f', '%s', '%s')
            );
        }

        if ($result) {
            yasr_save_option_imported_plugin('rmp');

            $string_to_return = esc_html__('Woot! All data have been imported!', 'yet-another-stars-rating');
            echo json_encode($string_to_return);
        }
    }
    die();
}

add_action( 'wp_ajax_yasr_import_mr', 'yasr_import_mr_callback' );

function yasr_import_mr_callback() {

    if($_POST['nonce']) {
        $nonce = $_POST['nonce'];
    } else {
        exit();
    }

    if (!wp_verify_nonce( $nonce, 'yasr-import-mr-action' ) ) {
        die('Error while checking nonce');
    }

    if (!current_user_can( 'manage_options' ) ) {
        die(esc_html__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
    }

    global $wpdb;

    $mr_exists = new YasrImportRatingPlugins();

    //get logs
    //With Multi Rating I need to import postmeta.
    $logs=$mr_exists->yasr_return_mr_data();

    if(empty($logs)) {
        echo json_encode(esc_html__('No Multi Rating data found'));
    } else {
        $result = false;

        /****** Insert logs ******/
        foreach ($logs as $column) {

            if($column->ratings_average > 5) {
                $column->ratings_average = 5;
            }

            for ($i=1; $i<=$column->ratings_users; $i++) {
                $result = $wpdb->replace(
                    YASR_LOG_TABLE,
                    array(
                        'post_id'      => $column->post_id,
                        'user_id'      => 0, //not stored on KK star rating
                        'vote'         => $column->ratings_average,
                        'date'         => 'multirating', //not stored KK star rating
                        'ip'           => 'multirating'//not stored KK star rating
                    ),
                    array('%d', '%d', '%f', '%s', '%s')
                );
            }
        }

        if ($result) {
            yasr_save_option_imported_plugin('mr');

            $string_to_return = esc_html__('Woot! All data have been imported!', 'yet-another-stars-rating');
            echo json_encode($string_to_return);
        }

    }

    die();
}

function yasr_save_option_imported_plugin($plugin) {

    //get actual data
    $plugin_imported = get_option('yasr_plugin_imported');
    //Add plugin just imported as a key
    $plugin_imported[$plugin] = array('date' => date('Y-m-d H:i:s'));
    //update option
    update_option('yasr_plugin_imported', $plugin_imported, false);
}

function yasr_import_plugin_alert_box($plugin, $number_of_queries) {

    echo '<div class="yasr-alert-box">';
        echo wp_kses_post(sprintf(__(
            'To import %s seems like %s %d %s INSERT queries are necessary. %s
                There is nothing wrong with that, but some hosting provider can have a query limit/hour. %s
                I strongly suggest to contact your hosting and ask about your plan limit',
            'yet-another-stars-rating'
        ),$plugin, '<strong>', $number_of_queries, '</strong>', '<br />','<br />'));
    echo '</div>';

}

