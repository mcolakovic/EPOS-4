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
 * class for get overallRating and visitorVotes values
 *
 * Class YasrDatabaseRatings
 */
class YasrDatabaseRatings {

    /**
     * Returns overall rating for single post or page
     *
     * @param $post_id void|bool|int
     *
     * @return mixed|null
     */
    public static function getOverallRating ($post_id=false) {
        //if values it's not passed get the post id, since version 1.6.9 this is just for yasr_add_schema function
        //and for a further check
        if (!is_int($post_id)) {
            $post_id = get_the_ID();
        }

        $overall_rating = get_post_meta($post_id, 'yasr_overall_rating', true);

        if (!$overall_rating || $overall_rating < 0) {
            $overall_rating = 0;
        }
        if($overall_rating > 5) {
            $overall_rating = 5;
        }
        return $overall_rating;
    }


    public static function getAllOverallRatings() {
        global $wpdb;

        $query = "SELECT pm.post_id,      
                         pm.meta_value   as vote, 
                         pm.meta_id      as id,
                         p.post_author   as user_id,
                         p.post_modified as date 
                  FROM $wpdb->postmeta   as pm,
                       $wpdb->posts      as p 
                  WHERE pm.meta_key = 'yasr_overall_rating'
                      AND p.ID = pm.post_id
                      AND pm.meta_value > 0";

        return $wpdb->get_results($query, ARRAY_A);

    }

    /**
     * Return Visitor Votes for a single post or page
     *
     * @param bool|integer $post_id
     *
     * @return array|bool|mixed|object|null
     *    array(
     *        'number_of_votes'  = (int)$user_votes->number_of_votes;
     *        'sum_votes'        = (int)$user_votes->sum_votes;
     *    )
     */
    public static function getVisitorVotes ($post_id = false) {
        global $wpdb;

        //if values it's not passed get the post id, most of cases and default one
        if (!is_int($post_id)) {
            $post_id = get_the_ID();
        }

        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT SUM(vote) as sum_votes, 
                            COUNT(vote)  as number_of_votes 
                        FROM " . YASR_LOG_TABLE .
                        "  WHERE post_id=%d
                           AND   vote > 0
                           AND   vote <= 5
                        ",
                $post_id
            )
        );

        $array_to_return = array(
            'number_of_votes' => 0,
            'sum_votes'       => 0
        );
        foreach ($result as $user_votes) {
            $array_to_return['number_of_votes'] = (int)$user_votes->number_of_votes;
            $array_to_return['sum_votes']       = (int)$user_votes->sum_votes;
        }
        return $array_to_return;
    }

    /**
     * Returns *ALL* visitor votes in YASR_LOG_TABLE
     * used in stats page
     *
     * @author Dario Curvino <@dudo>
     * @since 2.5.2
     *
     * @return array|object|null
     */
    public static function getAllVisitorVotes() {
        global $wpdb;

        $query = 'SELECT * FROM ' .YASR_LOG_TABLE.  ' ORDER BY date';

        return $wpdb->get_results($query, ARRAY_A);
    }

    /**
     * Check if an user has already rated, and if so, return the rating, or false otherwise
     *
     * @param int | bool $post_id
     *
     * @return bool|string
     */
    public static function visitorVotesHasUserVoted($post_id = false) {
        global $wpdb;

        $user_id      = get_current_user_id();

        //just to be safe
        if (!is_int($post_id)) {
            $post_id = get_the_ID();
        }

        if (!is_int($user_id)) {
            return false;
        }

        $rating = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT vote FROM "
                . YASR_LOG_TABLE .
                " WHERE post_id=%d 
                    AND user_id=%d 
                    LIMIT 1 ",
                $post_id, $user_id
            )
        );

        if ($rating === null) {
            $rating = false;
        } else {
            $rating = (int)$rating;
        }
        return $rating;
    }

}
