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
 * All functions needed to work with MultiSet
 *
 * Class YasrMultiSetData
 */
class YasrMultiSetData {

    /****** Get multi set name ******/
    public static function returnMultiSetNames() {
        global $wpdb;

        return $wpdb->get_results("SELECT * FROM " . YASR_MULTI_SET_NAME_TABLE . " ORDER BY set_id");
    }

    /**
     * Returns the first set id or false if not exists
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     * @return false|int
     */
    public static function returnFirstSetId() {
        global $wpdb;
        $set_id = false;

        $result = $wpdb->get_results(
            "SELECT set_id 
                        FROM " . YASR_MULTI_SET_NAME_TABLE . " 
                    ORDER BY set_id
                    LIMIT 1");

        if(is_array($result) && !empty($result[0]) && property_exists($result[0], 'set_id')) {
            $set_id = (int) $result[0]->set_id;
        }

        return $set_id;
    }

    /**
     * This function returns an multidimensional array of multiset ID and Fields
     *    array (
     *        array (
     *            'id' => '0',
     *            'name' => 'Field1',
     *        ),
     *        array (
     *            'id' => '1',
     *            'name' => 'Field2',
     *        ),
     *    )
     *
     * @param int $set_id
     * @return array|bool
     */

    public static function multisetFieldsAndID($set_id) {
        $set_id = (int)$set_id;

        global $wpdb;

        $result = $wpdb->get_results($wpdb->prepare(
            "SELECT f.field_id AS id, 
                    f.field_name AS name
                    FROM " . YASR_MULTI_SET_FIELDS_TABLE . " AS f
                    WHERE f.parent_set_id=%d
                    ORDER BY f.field_id
                    ", $set_id),
            ARRAY_A);

        if (empty($result)) {
            return false;
        }
        return $result;
    }

    /**
     * Get from the db all the values for VisitorMultiSet
     *
     * @param $post_id
     * @param $set_id
     * @param bool $visitor_multiset
     *
     * @return array|bool
     */
    public static function returnMultisetContent($post_id, $set_id, $visitor_multiset=false) {
        $set_id  = (int)$set_id;
        $post_id = (int)$post_id;

        if ($post_id === 0) {
            return false;
        }

        //set fields name and ids
        $set_fields = self::multisetFieldsAndID($set_id);

        if($set_fields === false) {
            return false;
        }

        if($visitor_multiset === true) {
            return self::returnArrayFieldsRatingsVisitor($set_id, $set_fields, $post_id);
        }

        //return
        return self::returnArrayFieldsRatingsAuthor($set_id, $set_fields, $post_id);
    }

    /** This functions returns an array with all the value to print the multiset
     *
     * array (
     *     array (
     *         'value_id' => 0,
     *         'value_name' => 'Field 1',
     *         'value_rating' => 3.5,
     *     ),
     *     array (
     *         'value_id' => 1,
     *         'value_name' => 'Field 2',
     *         'value_rating' => 3,
     *     )
     *
     * @param integer $set_id the set id
     * @param array   $set_fields an array with fields names and id
     * @param integer|bool $post_id the post_id
     *
     * @return bool | array
     */

    public static function returnArrayFieldsRatingsAuthor($set_id, $set_fields, $post_id=false) {
        $array_to_return = array ();
        $set_id = (int)$set_id;

        if (!$set_fields) {
            return false;
        }

        if(!is_int($post_id)) {
            $post_id = get_the_ID();
        }

        //get meta values (field id and rating)
        $set_post_meta_values = get_post_meta($post_id, 'yasr_multiset_author_votes', true);

        //index
        $i = 0;
        //always returns field id and name
        foreach ($set_fields as $fields_ids_and_names) {
            $array_to_return[$i]['id']     = (int) $fields_ids_and_names['id'];
            $array_to_return[$i]['name']   = $fields_ids_and_names['name'];
            $array_to_return[$i]['average_rating'] = 0;

            //if there is post meta
            if ($set_post_meta_values) {
                //first, loop saved fields and ratings
                foreach ($set_post_meta_values as $saved_set_id) {
                    //if the saved set is the same selected
                    if ($saved_set_id['set_id'] === $set_id) {
                        //loop the saved arrays
                        foreach ($saved_set_id['fields_and_ratings'] as $single_value) {
                            //if field id is the same, add the rating
                            if ($array_to_return[$i]['id'] === $single_value->field) {
                                //save the rating
                                $array_to_return[$i]['average_rating'] = $single_value->rating;
                            }
                        }
                    }
                }
            }
            //this is for list the set names
            $i ++;
        }
        return $array_to_return;
    }

    /** This functions returns an array with all the value to print the multiset
     *
     * array (
     *     array (
     *         'value_id' => 0,
     *         'value_name' => 'Field 1',
     *         'value_rating' => 3.5,
     *     ),
     *     array (
     *         'value_id' => 1,
     *         'value_name' => 'Field 2',
     *         'value_rating' => 3,
     *     )
     *
     * @param integer $set_id the set id
     * @param array $set_fields an array with fields names and id
     * @param integer|bool $post_id the post_id
     *
     * @return bool | array
     */

    public static function returnArrayFieldsRatingsVisitor($set_id, $set_fields, $post_id=false) {
        $array_to_return = array();

        global $wpdb;

        $set_id = (int)$set_id;

        if (!$set_fields) {
            return false;
        }

        if(!is_int($post_id)) {
            $post_id = get_the_ID();
        }

        //get meta values (field id and rating)
        $ratings = $wpdb->get_results($wpdb->prepare(
            "SELECT CAST((SUM(l.vote)/COUNT(l.vote)) AS DECIMAL(2,1)) AS average_rating,
                            COUNT(l.vote) AS number_of_votes,
                            field_id AS field
                        FROM " . YASR_LOG_MULTI_SET . " AS l
                        WHERE l.post_id=%d
                            AND   l.set_type=%d
                        GROUP BY l.field_id
                        ORDER BY l.field_id", $post_id, $set_id), ARRAY_A);

        //index
        $i = 0;
        //always returns field id and name
        foreach ($set_fields as $fields_ids_and_names) {
            $array_to_return[$i]['id']     = (int) $fields_ids_and_names['id'];
            $array_to_return[$i]['name']   = $fields_ids_and_names['name'];
            $array_to_return[$i]['average_rating'] = 0;
            $array_to_return[$i]['number_of_votes'] = 0;

            //if there is post meta
            if ($ratings) {
                //loop the saved arrays
                foreach ($ratings as $single_value) {
                    //if field id is the same, add the rating
                    if ($array_to_return[$i]['id'] === (int)$single_value['field']) {
                        $array_to_return[$i]['average_rating']  = $single_value['average_rating'];
                        $array_to_return[$i]['number_of_votes'] = (int)$single_value['number_of_votes'];
                    }
                }

            }
            //this is for list the set names
            $i ++;
        }

        return $array_to_return;
    }

    /**
     * @param int  $post_id
     * @param int  $set_id
     * @param bool $visitor_multiset
     *
     * Get the post_id and the set id, return the average (float)
     *
     * @return float|int
     */
    public static function returnMultiSetAverage($post_id, $set_id, $visitor_multiset) {
        $post_id = (int)$post_id;
        $set_id  = (int)$set_id;

        if ($visitor_multiset === true) {
            $multiset_content = self::returnMultisetContent($post_id, $set_id, true);
        } else {
            $multiset_content = self::returnMultisetContent($post_id, $set_id);
        }

        if (!is_array($multiset_content)) {
            return 0;
        }
        //default values
        $multiset_vote_sum = 0;
        $multiset_rows_number = 0;

        foreach ($multiset_content as $set_content) {
            $multiset_vote_sum    = $multiset_vote_sum + $set_content['average_rating'];
            $multiset_rows_number = $multiset_rows_number+1;
        }

        return round( $multiset_vote_sum / $multiset_rows_number, 1);
    }

    /**
     * This function loops an array of post_id that that has yasr_multiset_author_votes as meta key.
     * Returns an ordered array by rating's average for each post id
     *
     * @author Dario Curvino <@dudo>
     * @since  2.7.3
     *
     * @param  $array_post_id
     * @param  $set_id
     *
     * @return array
     */
    public static function returnMultiAuthorAverageArray ($array_post_id, $set_id) {
        $average_array = array();

        $i = 0;
        //loop the array
        foreach ($array_post_id as $post_id) {
            $average = self::returnMultiSetAverage($post_id->id, $set_id, false);
            if ($average > 0) {
                $average_array[$i]['post_id'] = $post_id->id;
                $average_array[$i]['rating']  = $average;
            }
            $i++;
        }

        //order the array by average rating
        array_multisort(array_column($average_array, 'rating'), SORT_DESC, SORT_NUMERIC, $average_array);

        return $average_array;
    }

    /**
     * Returns *ALL* multiset votes in YASR_LOG_MULTI_SET
     * used in stats page
     *
     * @author Dario Curvino <@dudo>
     * @since 2.5.2
     *
     * @return array|object|null
     */
    public static function returnAllLogMulti() {
        global $wpdb;

        $query = 'SELECT * FROM ' .YASR_LOG_MULTI_SET. ' ORDER BY date, set_type, post_id DESC';

        return $wpdb->get_results($query, ARRAY_A);
    }

}
