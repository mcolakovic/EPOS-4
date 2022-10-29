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

/**
 * Callback function for the spl_autoload_register above.
 *
 * @param $class
 */
function yasr_autoload_shortcodes($class) {
    /**
     * If the class being requested does not start with 'Yasr' prefix,
     * it's not in Yasr Project
     */
    if (0 !== strpos($class, 'Yasr')) {
        return;
    }
    $file_name =  YASR_ABSOLUTE_PATH_INCLUDES . '/shortcodes/classes/' . $class . '.php';

    // check if file exists, just to be sure
    if (file_exists($file_name)) {
        require($file_name);
    }
}

//AutoLoad YASR Shortcode Classes, only when a object is created
spl_autoload_register('yasr_autoload_shortcodes');

/****** Add shortcode for overall rating ******/
add_shortcode('yasr_overall_rating', 'shortcode_overall_rating_callback');

/**
 * @param $atts
 * @param $content
 * @param $shortcode_tag
 *
 * @return string|void|null
 */
function shortcode_overall_rating_callback ($atts, $content=false, $shortcode_tag=false) {
    if (YASR_SHOW_OVERALL_IN_LOOP === 'disabled' && !is_singular() && is_main_query()) {
        return;
    }
    $overall_rating = new YasrOverallRating($atts, $shortcode_tag);

    return $overall_rating->returnShortcode();
} //end function


/****** Add shortcode for user vote ******/

add_shortcode('yasr_visitor_votes', 'shortcode_visitor_votes_callback');

/**
 * @param      $atts
 * @param bool $content
 * @param bool $shortcode_tag
 *
 * @return string|void|null
 */
function shortcode_visitor_votes_callback($atts, $content=false, $shortcode_tag=false) {
    if (YASR_SHOW_VISITOR_VOTES_IN_LOOP === 'disabled' && !is_singular() && is_main_query()) {
        return;
    }

    $visitor_votes = new YasrVisitorVotes($atts, $shortcode_tag);

    return $visitor_votes->returnShortcode();

} //End function shortcode_visitor_votes_callback

/****** Add shortcode for multiple set ******/

add_shortcode ('yasr_multiset',  'yasr_multiset_callback');

/**
 * @param      $atts
 * @param bool $content
 * @param bool $shortcode_tag
 *
 * @return bool|string
 */
function yasr_multiset_callback($atts, $content, $shortcode_tag) {
    $multiset = new YasrMultiSet($atts, $shortcode_tag);

    return $multiset->printMultiset();
}

/****** Add shortcode for multiset writable by users  ******/

add_shortcode ('yasr_visitor_multiset', 'yasr_visitor_multiset_callback');

/**
 * @param      $atts
 * @param bool $content
 * @param bool $shortcode_tag
 *
 * @return string
 */
function yasr_visitor_multiset_callback($atts, $content, $shortcode_tag) {
    $multiset = new YasrVisitorMultiSet($atts, $shortcode_tag);
    return $multiset->printVisitorMultiSet();
}


/*
 * @deprecated deprecated since version 2.6.2
 * @todo remove DEC 2023
 */
add_shortcode ('yasr_top_ten_highest_rated', 'yasr_ov_ranking_callback');

/*
 * Shortcode to display highest rated posts by overall_rating
 * @since 2.6.2
 */
add_shortcode ('yasr_ov_ranking', 'yasr_ov_ranking_callback');

/**
 * @param $atts
 * @param $content
 * @param $shortcode_tag
 *
 * @return string
 */
function yasr_ov_ranking_callback($atts, $content, $shortcode_tag) {
    $top_ten_highest_obj = new YasrRankings(false, $shortcode_tag);

    return $top_ten_highest_obj->returnHighestRatedOverall($atts);
} //End function


/****** Add top 10 most rated / highest rated post *****/
add_shortcode ('yasr_most_or_highest_rated_posts', 'yasr_most_or_highest_rated_posts_callback');
/**
 * @param $atts
 * @param $content
 * @param $shortcode_tag
 *
 * @return string
 */
function yasr_most_or_highest_rated_posts_callback($atts, $content, $shortcode_tag) {
    $most_highest_obj = new YasrRankings(false, $shortcode_tag);

    return $most_highest_obj->vvReturnMostHighestRated($atts);
} //End function


/*
 * @deprecated deprecated since version 2.6.2
 * @todo remove DEC 2023
 */
add_shortcode ('yasr_top_5_reviewers', 'yasr_ranking_users_callback');

/*
 * Shortcode to display most active reviewers
 * @since 2.6.2
 */
add_shortcode ('yasr_top_reviewers', 'yasr_ranking_users_callback');

/*
 * @deprecated deprecated since version 2.6.2
 * @todo remove DEC 2023
 */
add_shortcode ('yasr_top_ten_active_users', 'yasr_ranking_users_callback');

/*
 * Shortcode to display most active reviewers
 * @since 2.6.2
 */
add_shortcode ('yasr_most_active_users', 'yasr_ranking_users_callback');

/**
 * @author Dario Curvino <@dudo>
 *
 * @param $atts
 * @param $content
 * @param $shortcode_tag
 *
 * @return string
 */
function yasr_ranking_users_callback ($atts, $content, $shortcode_tag) {
    $ranking_users_obj = new YasrNoStarsRankings(false, $shortcode_tag);


    if($shortcode_tag === 'yasr_top_reviewers' || $shortcode_tag === 'yasr_top_5_reviewers') {
        return $ranking_users_obj->returnTopReviewers($atts);
    }

    return $ranking_users_obj->returnTopUsers($atts);
} //End users rankings


add_shortcode ('yasr_multi_set_ranking', 'yasr_multi_set_ranking_callback');

function yasr_multi_set_ranking_callback($atts, $content, $shortcode_tag) {
    $yasr_ranking = new YasrRankings($atts, $shortcode_tag);
    return $yasr_ranking->returnMulti($atts);
} //End function

add_shortcode ('yasr_visitor_multi_set_ranking', 'yasr_visitor_multi_set_ranking_callback');

function yasr_visitor_multi_set_ranking_callback($atts, $content, $shortcode_tag) {
    $yasr_ranking = new YasrRankings($atts, $shortcode_tag);

    return $yasr_ranking->returnMultiVisitor($atts);
} //End function

/**
 * Add shortcode yasr_users_log_frontend and the ajax action
 */
add_shortcode('yasr_user_rate_history', 'yasr_users_front_widget_callback');
add_action('wp_ajax_yasr_change_user_log_page_front', 'yasr_users_front_widget_callback');
function yasr_users_front_widget_callback() {
    $log_widget = new YasrLogDashboardWidget();
    return $log_widget->userWidgetShortcode();
} //End callback function

