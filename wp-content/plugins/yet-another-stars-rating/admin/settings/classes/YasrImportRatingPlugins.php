<?php
/*

Copyright 2020 Dario Curvino (email : d.curvino@tiscali.it)

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

/****** Check for previous rate my post INSTALLATION *******/
class YasrImportRatingPlugins {

    //Search for WP-PostRatings
    public function yasr_search_wppr() {
        //only check for active plugin, since import from table will be not used
        if (is_plugin_active('wp-postratings/wp-postratings.php')) {
            return true;
        }
        return false;
    }

    //Search for KK STar Rating
    public function yasr_search_kksr() {
        //only check for active plugin, since import from table will be not used
        if (is_plugin_active('kk-star-ratings/index.php')) {
            return true;
        }
        return false;
    }

    //Search for Rate My Post
    public function yasr_search_rmp() {
        if (is_plugin_active('rate-my-post/rate-my-post.php')) {
            return true;
        }
        global $wpdb;

        $rmp_table = $wpdb->prefix . 'rmp_analytics';

        if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE '%s'", $rmp_table)) === $rmp_table) {
            return true;
        }
        return false;
    }

    //Search for Multi Rating
    public function yasr_search_mr() {
        //only check for active plugin, since import from table will be not used
        if (is_plugin_active('multi-rating/multi-rating.php')) {
            return true;
        }
        return false;
    }

    public function yasr_count_wppr_query_number() {
        $number_of_query_transient = get_transient('yasr_wppr_import_query_number');

        if ($number_of_query_transient !== false) {
            return $number_of_query_transient;
        }

        $logs = $this->yasr_return_wppr_data();

        //set counter to 0
        $i = 0;

        if (empty($logs)) {
            return 0;
        }

        //count insert queries
        foreach ($logs as $column) {
            for ($j = 1; $j <= $column->ratings_users; $j++) {
                $i++;
            }
        }

        set_transient('yasr_wppr_import_query_number', $i, DAY_IN_SECONDS);

        return $i;

    }

    public function yasr_count_kksr_query_number() {
        $number_of_query_transient = get_transient('yasr_kksr_import_query_number');

        if ($number_of_query_transient !== false) {
            return $number_of_query_transient;
        }

        $logs = $this->yasr_return_kksr_data();

        //set counter to 0
        $i = 0;

        if (empty($logs)) {
            return 0;
        }

        //count insert queries
        foreach ($logs as $column) {
            for ($j = 1; $j <= $column->ratings_users; $j++) {
                $i++;
            }
        }

        set_transient('yasr_kksr_import_query_number', $i, DAY_IN_SECONDS);

        return $i;

    }

    public function yasr_count_rmp_query_number() {
        global $wpdb;

        $number_of_query_transient = get_transient('yasr_rmp_import_query_number');

        if ($number_of_query_transient !== false) {
            return $number_of_query_transient;
        }

        $logs = $this->yasr_return_rmp_data();

        if (empty($logs)) {
            return 0;
        }

        set_transient('yasr_rmp_import_query_number', $wpdb->num_rows, DAY_IN_SECONDS);

        return $wpdb->num_rows;

    }

    public function yasr_count_mr_query_number() {
        $number_of_query_transient = get_transient('yasr_mr_import_query_number');

        if ($number_of_query_transient !== false) {
            return $number_of_query_transient;
        }

        $logs = $this->yasr_return_mr_data();

        //set counter to 0
        $i = 0;

        if (empty($logs)) {
            return 0;
        }

        //count insert queries
        foreach ($logs as $column) {
            for ($j = 1; $j <= $column->ratings_users; $j++) {
                $i++;
            }
        }
        set_transient('yasr_mr_import_query_number', $i, DAY_IN_SECONDS);

        return $i;

    }

    //Import WpPostRating Data
    public function yasr_return_wppr_data() {
        global $wpdb;

        $logs = $wpdb->get_results(
            "SELECT pm.post_id, 
                        MAX(CASE WHEN pm.meta_key = 'ratings_average' THEN pm.meta_value END) as ratings_average,
                        MAX(CASE WHEN pm.meta_key = 'ratings_users' THEN pm.meta_value END) as ratings_users
                   FROM $wpdb->postmeta as pm,
                         $wpdb->posts as p
                   WHERE pm.meta_key IN ('ratings_average', 'ratings_users')
                       AND pm.meta_value <> 0
                       AND pm.post_id = p.ID
                   GROUP BY pm.post_id
                   ORDER BY pm.post_id"
        );

        if (empty($logs)) {
            return 0;
        }

        return $logs;
    }

    //Import KK Star Rating Data
    public function yasr_return_kksr_data() {
        global $wpdb;

        $logs = $wpdb->get_results(
            "SELECT pm.post_id, 
                        MAX(CASE WHEN pm.meta_key = '_kksr_avg' THEN pm.meta_value END) as ratings_average,
                        MAX(CASE WHEN pm.meta_key = '_kksr_casts' THEN pm.meta_value END) as ratings_users
                    FROM $wpdb->postmeta as pm,
                         $wpdb->posts as p
                    WHERE pm.meta_key IN ('_kksr_avg', '_kksr_casts')
                        AND pm.meta_value <> 0
                        AND pm.post_id = p.ID
                    GROUP BY pm.post_id
                    ORDER BY pm.post_id"
        );

        if (empty($logs)) {
            return 0;
        }

        return $logs;
    }

    public function yasr_return_rmp_data() {
        global $wpdb;

        $rmp_table = $wpdb->prefix . 'rmp_analytics';

        //get logs
        $logs = $wpdb->get_results(
            "SELECT rmp.post AS post_id,
                       rmp.value as vote, 
                       rmp.time AS date,
                       p.ID
                    FROM $rmp_table AS rmp, 
                        $wpdb->posts AS p
                    WHERE rmp.post = p.id"
        );

        if (empty($logs)) {
            return 0;
        }

        return $logs;
    }

    //Import Multi Rating Data
    public function yasr_return_mr_data() {
        global $wpdb;

        $logs = $wpdb->get_results(
            "SELECT pm.post_id, 
                        MAX(CASE WHEN pm.meta_key = 'mr_rating_results_star_rating' THEN pm.meta_value END) as ratings_average,
                        MAX(CASE WHEN pm.meta_key = 'mr_rating_results_count_entries' THEN pm.meta_value END) as ratings_users
                    FROM $wpdb->postmeta as pm,
                         $wpdb->posts as p
                    WHERE pm.meta_key IN ('mr_rating_results_star_rating', 'mr_rating_results_count_entries')
                        AND pm.meta_value <> 0
                        AND pm.post_id = p.ID
                    GROUP BY pm.post_id 
                    ORDER BY pm.post_id"
        );

        if (empty($logs)) {
            return 0;
        }

        return $logs;
    }

}