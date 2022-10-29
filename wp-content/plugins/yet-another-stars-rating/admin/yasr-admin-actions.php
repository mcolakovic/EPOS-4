<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

add_action('plugins_loaded', 'yasr_edit_category_form');

function yasr_edit_category_form () {
    if (current_user_can('manage_options')) {
        YasrEditCategory::init();
    }
}

/****** Adding logs widget to the dashboard ******/

add_action('plugins_loaded', 'yasr_add_action_dashboard_widget_log');

function yasr_add_action_dashboard_widget_log() {
    //This is for the admins (show all votes in the site)
    if (current_user_can('manage_options')) {
        add_action('wp_dashboard_setup', 'yasr_add_dashboard_widget_log');
    }

    //This is for all the users to see where they've voted
    add_action('wp_dashboard_setup', 'yasr_add_dashboard_widget_user_log');
}

function yasr_add_dashboard_widget_log() {
    wp_add_dashboard_widget(
        'yasr_widget_log_dashboard', //slug for widget
        'Recent Ratings', //widget name
        'yasr_widget_log_dashboard_callback' //function callback
    );
}

//This add a dashboard log for every users
function yasr_add_dashboard_widget_user_log() {
    wp_add_dashboard_widget(
        'yasr_users_dashboard_widget', //slug for widget
        'Your Ratings', //widget name
        'yasr_users_dashboard_widget_callback' //function callback
    );
}

/****** Delete data value from yasr tabs when a post or page is deleted
 * Added since yasr 0.3.3
 ******/
add_action('admin_init', 'admin_init_delete_data_on_post_callback');

function admin_init_delete_data_on_post_callback() {

    if (current_user_can('delete_posts')) {
        add_action('delete_post', 'yasr_erase_data_on_post_page_remove_callback');
    }

}

function yasr_erase_data_on_post_page_remove_callback($post_id) {
    global $wpdb;

    delete_metadata('post', $post_id, 'yasr_overall_rating');
    delete_metadata('post', $post_id, 'yasr_review_type');
    delete_metadata('post', $post_id, 'yasr_multiset_author_votes');

    //Delete multi value
    $wpdb->delete(
        YASR_LOG_MULTI_SET,
        array(
            'post_id' => $post_id
        ),
        array(
            '%d'
        )
    );

    $wpdb->delete(
        YASR_LOG_TABLE,
        array(
            'post_id' => $post_id
        ),
        array(
            '%d'
        )
    );


}
