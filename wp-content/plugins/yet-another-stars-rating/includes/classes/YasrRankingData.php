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

class YasrRankingData {

    /**
     * Run $wpdb->get_results for overall Rating
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.2
     *
     * @param bool|array $atts
     *
     * @return array|false|object|void
     */
    public static function rankingOverallGetResults($atts) {
        global $wpdb;

        //return custom query_result here
        //must returns rating and post_id
        $query      = apply_filters('yasr_rankings_query_ov', $atts);

        //if query_results === $sql_params means that filters doesn't exists
        if($query === $atts) {
            //default query_results
            $query = "SELECT pm.meta_value AS rating, 
                         pm.post_id AS post_id
                  FROM $wpdb->postmeta AS pm, 
                       $wpdb->posts AS p
                  WHERE  pm.post_id = p.ID
                      AND p.post_status = 'publish'
                      AND pm.meta_key = 'yasr_overall_rating'
                      AND pm.meta_value > 0
                  ORDER BY pm.meta_value DESC,
                           pm.post_id 
                  LIMIT 10";

            $query_results = $wpdb->get_results($query);
        } else {
            $query_results = $query;
        }

        if ($query_results) {
            return $query_results;
        }
        return false;
    }

    /**
     * Returns yasr_most_or_highest_rated_posts with no params and 10 rows.
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.2
     *
     * @param array      $atts
     * @param            $ranking
     *
     * @return array|false|object|void
     */

    public static function rankingVVGetResults($atts, $ranking) {
        global $wpdb;

        //hooks here to return a query
        $query      = apply_filters('yasr_rankings_query_vv', $atts, $ranking);

        //if no custom query is hooked
        if($query === $atts) {
            $common_query = "SELECT post_id, 
                COUNT(post_id) AS number_of_votes,
                ROUND(SUM(vote) / COUNT(post_id),1) AS rating
            FROM " . YASR_LOG_TABLE . ",
                $wpdb->posts AS p
            WHERE post_id = p.ID
                AND p.post_status = 'publish'
            GROUP BY post_id
                HAVING number_of_votes > 1
            ";

            if ($ranking === 'highest') {
                $order_by = ' ORDER BY rating DESC, number_of_votes DESC';
            }
            else {
                $order_by = ' ORDER BY number_of_votes DESC, rating DESC, post_id DESC';
            }

            $limit = ' LIMIT 10';
            $query = $common_query . $order_by . $limit;

            $query_results = $wpdb->get_results($query);
        } else {
            $query_results = $query;
        }

        if ($query_results) {
            return $query_results;
        }
        return false;
    }

    /***
     * @author Dario Curvino <@dudo>
     * @since 2.6.3
     * @param $atts
     *
     * @return array|false|object|void
     */
    public static function rankingTopReviewers($atts) {
        global $wpdb;

        //return custom query here
        //must returns rating and post_id
        $query      = apply_filters('yasr_rankings_query_tr', $atts);

        //if query === $sql_params (both are falses) means that filters doesn't exists
        if($query === $atts) {
            $query = "SELECT COUNT( pm.post_id ) AS total_count,
                          p.post_author    AS user,
                          u.user_login     AS name
                      FROM $wpdb->posts    AS p, 
                           $wpdb->postmeta AS pm,
                           $wpdb->users    AS u
                      WHERE pm.post_id = p.ID
                          AND pm.meta_key = 'yasr_overall_rating'
                          AND p.post_status = 'publish'
                          AND p.post_author = u.ID
                      GROUP BY user
                      ORDER BY total_count DESC
                    LIMIT 5";

            $query_results = $wpdb->get_results($query);

        }  else {
            $query_results = $query;
        }

        if ($query_results) {
            return $query_results;
        }
        return false;
    }

    /***
     * @author Dario Curvino <@dudo>
     * @since 2.6.3
     * @param $atts
     *
     * @return array|false|object|void
     */
    public static function rankingTopUsers($atts) {
        global $wpdb;

        //return custom query here
        //must returns rating and post_id
        $query     = apply_filters('yasr_rankings_query_tu', $atts);

        //if query === $sql_params (both are falses) means that filters doesn't exists
        if($query === $atts) {
            $query = 'SELECT COUNT(user_id) as total_count, 
                        user_id as user
                    FROM ' . YASR_LOG_TABLE . ", 
                        $wpdb->posts AS p
                    WHERE  post_id = p.ID
                        AND p.post_status = 'publish'
                    GROUP BY user_id
                    ORDER BY ( total_count ) DESC
                    LIMIT 10";

            $query_results = $wpdb->get_results($query);

        }  else {
            $query_results = $query;
        }

        if ($query_results) {
            return $query_results;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.2
     *
     * @param        $set_id
     * @param  false $sql_atts
     *
     * @return bool|array
     */
    public static function rankingMulti($set_id, $sql_atts=false) {
        global $wpdb;
        if($set_id === NULL) {
            $set_id = YASR_FIRST_SETID;
        }

        $set_id = (int)$set_id;

        //hooks here to return a query
        $query_result     = apply_filters('yasr_rankings_multi_query', $sql_atts, $set_id);

        //if hook has run
        if ($query_result !== $sql_atts) {
            //but don't return an array or array is empty, return false
            if (!is_array($query_result) || empty($query_result)) {
                return false;
            }
            //else return hooks result
            return $query_result;
        }

        //if no hook is found in $query_result, return default ranking
        if($query_result === $sql_atts) {
            //Create an array of post_id that has meta key = yasr_multiset_author_votes
            $array_post_id
                = $wpdb->get_results(
                "SELECT pm.post_id AS id
                FROM $wpdb->postmeta AS pm, 
                     $wpdb->posts    AS p
                WHERE  pm.post_id = p.ID
                    AND p.post_status = 'publish'
                    AND pm.meta_key = 'yasr_multiset_author_votes'
                ORDER BY pm.post_id"
            );

            if (!is_array($array_post_id) || empty($array_post_id)) {
                return false;
            }

            //set fields name and ids
            $average_array = YasrMultiSetData::returnMultiAuthorAverageArray($array_post_id, $set_id);

            //Limit the array to N results
            return array_slice($average_array, 0, 10);
        }

        //should never happens
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.2
     *
     * @param int|bool|string $set_id
     * @param string          $ranking
     * @param false           $sql_atts
     *
     * @return array|false|object
     */
    public static function rankingMultiVV ($set_id, $ranking='most', $sql_atts=false) {
        global $wpdb;
        //if set_id is not set (e.g. in rest parameter setid is not set)
        if($set_id === NULL) {
            $set_id = YASR_FIRST_SETID;
        }

        $set_id = (int)$set_id;

        //hooks here to return a query
        $query     = apply_filters('yasr_rankings_multivv_query', $sql_atts, $ranking, $set_id);

        //if no custom query is hooked
        if($query === $sql_atts) {
            $query = $wpdb->prepare(
                "SELECT CAST((SUM(l.vote)/COUNT(l.vote)) AS DECIMAL(2,1)) AS rating, 
                           COUNT(l.vote) AS number_of_votes, 
                           l.post_id
                       FROM " . YASR_LOG_MULTI_SET . " AS l,
                           $wpdb->posts AS p
                       WHERE l.set_type = %d
                           AND p.ID = l.post_id
                           AND p.post_status = 'publish'
                       GROUP BY l.post_id",
                    '%d'
                );
            if ($ranking === 'highest') {
                $query .= ' ORDER BY rating DESC';
            }
            else {
                $query .= ' ORDER BY number_of_votes DESC';
            }
            $query .= ' LIMIT 10';

            $query_results = $wpdb->get_results($query);

        }  else {
            $query_results = $query;
        }

        if ($query_results) {
            return $query_results;
        }
        return false;
    }

}