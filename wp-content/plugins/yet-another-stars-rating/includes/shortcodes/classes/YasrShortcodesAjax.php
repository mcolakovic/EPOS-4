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
 * This function adds ajax action needed for shortcodes
 *
 * @author Dario Curvino <@dudo>
 * @since 2.7.7
 * Class YasrShortcodesAjax
 */
class YasrShortcodesAjax {

    public function init() {
        if (YASR_ENABLE_AJAX === 'yes') {
            //load vv with ajax
            add_action('wp_ajax_yasr_load_vv',        array('YasrShortcodesAjax', 'returnArrayVisitorVotes'));
            add_action('wp_ajax_nopriv_yasr_load_vv', array('YasrShortcodesAjax', 'returnArrayVisitorVotes'));

            add_action('wp_ajax_yasr_load_rankings',        array('YasrShortcodesAjax', 'rankingData'));
            add_action('wp_ajax_nopriv_yasr_load_rankings', array('YasrShortcodesAjax', 'rankingData'));
        }

        //VV save rating
        add_action('wp_ajax_yasr_send_visitor_rating', array($this, 'saveVV'));
        add_action('wp_ajax_nopriv_yasr_send_visitor_rating', array($this, 'saveVV'));

        //MV save rating
        add_action('wp_ajax_yasr_visitor_multiset_field_vote', array($this, 'saveMV'));
        add_action('wp_ajax_nopriv_yasr_visitor_multiset_field_vote', array($this, 'saveMV'));

        //VV load stats
        if(YASR_VISITORS_STATS === 'yes') {
            add_action('wp_ajax_yasr_stats_visitors_votes', array($this, 'returnVVStats'));
            add_action('wp_ajax_nopriv_yasr_stats_visitors_votes', array($this, 'returnVVStats'));
        }

    }

    /**
     * Save or update rating for yasr_visitor_votes
     *
     * @author Dario Curvino <@dudo>
     * @since  refactor in 2.7.7
     */
    public function saveVV() {
        if (isset($_POST['rating'], $_POST['post_id'], $_POST['nonce_visitor'])) {
            $rating        = (int) $_POST['rating'];
            $post_id       = (int) $_POST['post_id'];
            $nonce_visitor = $_POST['nonce_visitor'];
            $is_singular   = $_POST['is_singular'];
        }
        else {
            die();
        }

        $array_action_visitor_vote = array('post_id' => $post_id, 'is_singular' => $is_singular);

        do_action('yasr_action_on_visitor_vote', $array_action_visitor_vote);

        $nonce_response = self::validNonce($nonce_visitor, 'yasr_nonce_vv');
        if($nonce_response !== true) {
            die ($nonce_response);
        }

        if ($rating < 1) {
            $rating = 1;
        }
        elseif ($rating > 5) {
            $rating = 5;
        }

        $current_user_id = get_current_user_id();
        $ip_address      = yasr_get_ip();

        $result_update_log = null; //avoid undefined
        $result_insert_log = null; //avoid undefined

        if (is_user_logged_in()) {
            //try to update first, if fails the do the insert
            $result_update_log = $this->vvUpdateRating($post_id, $current_user_id, $rating, $ip_address);

            //insert the new row
            //use ! instead of === FALSE
            if (!$result_update_log) {
                $result_insert_log = $this->vvSaveRating($post_id, $current_user_id, $rating, $ip_address);
            }

        } //if user is not logged in insert
        else {
            $result_insert_log = $this->vvSaveRating($post_id, $current_user_id, $rating, $ip_address);
        }

        if ($result_update_log || $result_insert_log) {
            echo json_encode($this->vvReturnResponse($post_id, $rating, $result_update_log));
        }

        die(); // this is required to return a proper result

    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     *
     * @param $post_id
     * @param $user_id
     * @param $rating
     * @param $ip_address
     *
     * @return bool|int
     */
    public function vvSaveRating($post_id, $user_id, $rating, $ip_address) {
        global $wpdb;
        return $wpdb->replace(
            YASR_LOG_TABLE, array(
                'post_id' => $post_id,
                'user_id' => $user_id,
                'vote'    => $rating,
                'date'    => date('Y-m-d H:i:s'),
                'ip'      => $ip_address
            ), array('%d', '%d', '%d', '%s', '%s', '%s')
        );
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     *
     * @param $post_id
     * @param $user_id
     * @param $rating
     * @param $ip_address
     *
     * @return bool|int
     */
    public function vvUpdateRating($post_id, $user_id, $rating, $ip_address) {
        global $wpdb;

        return $wpdb->update(
            YASR_LOG_TABLE, array(
                'post_id' => $post_id,
                'user_id' => $user_id,
                'vote'    => $rating,
                'date'    => date('Y-m-d H:i:s'),
                'ip'      => $ip_address
            ), array(
                'post_id' => $post_id,
                'user_id' => $user_id
            ), array('%d', '%d', '%d', '%s', '%s', '%s'), array('%d', '%d')
        );
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     *
     * @param $post_id
     * @param $rating
     * @param $result_update_log
     *
     * @return array
     */
    public function vvReturnResponse($post_id, $rating, $result_update_log) {
        $row_exists = YasrDatabaseRatings::getVisitorVotes($post_id);

        $user_votes_sum  = $row_exists['sum_votes'];
        $number_of_votes = $row_exists['number_of_votes'];

        //customize visitor_votes cookie name
        $cookiename = apply_filters('yasr_vv_cookie', 'yasr_visitor_vote_cookie');

        $data_to_save = array(
            'post_id' => $post_id,
            'rating'  => $rating
        );

        yasr_setcookie($cookiename, $data_to_save);

        $total_rating  = ($user_votes_sum / $number_of_votes);
        $medium_rating = round($total_rating, 1);

        $rating_saved_text = '';

        //Default text when rating is saved
        if ($result_update_log) {
            $rating_saved_text = apply_filters('yasr_vv_updated_text', $rating_saved_text);
        }
        else {
            $rating_saved_text = apply_filters('yasr_vv_saved_text', $rating_saved_text);
        }

        $rating_saved_span = '<span class="yasr-small-block-bold" id="yasr-vote-saved">'
            . wp_kses_post(htmlspecialchars_decode($rating_saved_text)) .
            '</span>';

        return array(
            'status'            => 'success',
            'number_of_votes'   => $number_of_votes,
            'average_rating'    => $medium_rating,
            'rating_saved_text' => $rating_saved_span
        );
    }

    /**
     * Return response for Ajax and Rest API
     *
     * @author Dario Curvino <@dudo>
     * @since  moved in YasrShortcodeAjax since 2.7.7
     * @return array
     */
    public static function returnArrayVisitorVotes() {
        $post_id = false;
        if (isset($_GET['post_id'])) {
            $post_id = (int)$_GET['post_id'];
        }

        //default values
        $array_to_return = array(
            'number_of_votes'  => 0,
            'sum_votes'        => 0,
            'stars_attributes' => array(
                'read_only'   => true,
                'span_bottom' => false
            )
        );

        $cookie_value  = YasrVisitorVotes::checkCookie($post_id);
        $stars_enabled = YasrShortcode::starsEnalbed($cookie_value);

        //if user is enabled to rate, readonly must be false
        if ($stars_enabled === 'true_logged' || $stars_enabled === 'true_not_logged') {
            $array_to_return['stars_attributes']['read_only'] = false;
        }

        $array_to_return['stars_attributes']['span_bottom'] = YasrVisitorVotes::showTextBelowStars($cookie_value, $post_id);

        $array_visitor_votes = YasrDatabaseRatings::getVisitorVotes($post_id);

        $array_to_return['number_of_votes'] = $array_visitor_votes['number_of_votes'];
        $array_to_return['sum_votes']       = $array_visitor_votes['sum_votes'];

        //this means is an ajax call
        if (isset($_GET['action']) && $_GET['action'] === 'yasr_load_vv') {
            $array_to_echo['yasr_visitor_votes'] = $array_to_return;
            echo json_encode($array_to_echo);
            die();
        }

        //return rest response
        return $array_to_return;
    }


    /**
     * Save or update rating for yasr_visitor_multiset
     *
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     */
    public function saveMV() {
        if (isset($_POST['post_id']) && isset($_POST['rating']) && isset($_POST['set_id'])) {
            $post_id  = (int) $_POST['post_id'];
            $rating   = $_POST['rating'];
            $set_id   = (int) $_POST['set_id'];
            $nonce    = $_POST['nonce'];

            if (!is_int($post_id) || !is_int($set_id)) {
                exit("Missing post id or set type");
            }

            if (!is_array($rating)) {
                exit("Error with rating");
            }
        } else {
            exit();
        }

        $nonce_response = self::validNonce($nonce, 'yasr_nonce_insert_visitor_rating_multiset');
        if($nonce_response !== true) {
            die ($nonce_response);
        }

        $current_user_id = get_current_user_id();
        $ip_address      = yasr_get_ip();

        $array_action_visitor_multiset_vote = array('post_id' => $post_id);

        do_action('yasr_action_on_visitor_multiset_vote', $array_action_visitor_multiset_vote);

        $array_error = array();

        //clean array, so if an user rate same field twice, take only the last rating
        $cleaned_array = yasr_unique_multidim_array($rating, 'field');

        //this is a counter: if at the end of the foreach it still 0, means that an user rated in a set
        //and then submit another one
        $counter_matched_fields = 0;

        foreach ($cleaned_array as $rating_values) {
            $rating_postid = (int)$rating_values['postid'];
            $rating_setid  = (int)$rating_values['setid'];

            //check if the set id in the array is the same of the clicked
            if ($rating_postid === $post_id && $rating_setid === $set_id) {
                //increase the counter
                $counter_matched_fields = $counter_matched_fields + 1;

                $id_field = (int)$rating_values['field'];
                $rating   = $rating_values['rating'];

                //if the user is logged
                if(is_user_logged_in()) {
                    //first try to update the vote
                    $update_query_success = $this->mvUpdateRating ($id_field, $set_id, $post_id, $rating, $current_user_id, $ip_address);

                    //if the update fails
                    if (!$update_query_success) {
                        //insert as new rating
                        $insert_query_success = $this->mvSaveRating ($id_field, $set_id, $post_id, $rating, $current_user_id, $ip_address);
                        //if rating is not saved, it is an error
                        if (!$insert_query_success) {
                            $array_error[] = 1;
                        }
                    }
                }
                //else try to insert vote
                else {
                    $replace_query_success = $this->mvSaveRating ($id_field, $set_id, $post_id, $rating, $current_user_id, $ip_address);
                    //if rating is not saved, it is an error
                    if (!$replace_query_success) {
                        $array_error[] = 1;
                    }
                }
            } //End if $rating_values['postid'] == $post_id

        } //End foreach ($rating as $rating_values)

        if ($counter_matched_fields === 0) {
            $array_error[] = 1;
        }

        $error_found = false;

        foreach ($array_error as $error) {
            if ($error === 1) {
                $error_found = true;
            }
        }

        //echo response
        echo json_encode($this->mvReturnResponse ($error_found, $post_id, $set_id));

        die();

    } //End callback function

    /**
     * Save rating for multi set visitor
     *
     * @author Dario Curvino <@dudo>
     * @since 2.7.7
     * @param $id_field
     * @param $set_id
     * @param $post_id
     * @param $rating
     * @param $user_id
     * @param $ip_address
     *
     * @return bool|int
     */
    public function mvSaveRating ($id_field, $set_id, $post_id, $rating, $user_id, $ip_address) {
        global $wpdb;

        return $wpdb->replace(
            YASR_LOG_MULTI_SET,
            array(
                'field_id' => $id_field,
                'set_type' => $set_id,
                'post_id'  => $post_id,
                'vote'     => $rating,
                'user_id'  => $user_id,
                'date'     => date('Y-m-d H:i:s'),
                'ip'       => $ip_address
            ),
            array("%d", "%d", "%d", "%d", "%d", "%s", "%s")
        );
    }

    /**
     * Update rating for multi set visitor
     *
     * @author Dario Curvino <@dudo>
     * @since 2.7.7
     * @param $id_field
     * @param $set_id
     * @param $post_id
     * @param $rating
     * @param $user_id
     * @param $ip_address
     *
     * @return bool|int
     */
    public function mvUpdateRating ($id_field, $set_id, $post_id, $rating, $user_id, $ip_address) {
        global $wpdb;

        return $wpdb->update(
            YASR_LOG_MULTI_SET,
            array(
                'field_id' => $id_field,
                'set_type' => $set_id,
                'post_id'  => $post_id,
                'vote'     => $rating,
                'user_id'  => $user_id,
                'date'     => date( 'Y-m-d H:i:s' ),
                'ip'       => $ip_address
            ),
            array(
                'field_id' => $id_field,
                'set_type' => $set_id,
                'post_id'  => $post_id,
                'user_id'  => $user_id
            ),
            array( "%d", "%d", "%d", "%d", "%d", "%s", "%s" ),
            array( "%d", "%d", "%d", "%d" )
        );
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.2
     *
     * @param $error_found
     * @param $post_id
     * @param $set_id
     *
     * @return array
     */
    public function mvReturnResponse ($error_found, $post_id, $set_id) {
        if (!$error_found) {
            $cookiename = apply_filters('yasr_mv_cookie', 'yasr_multi_visitor_cookie');

            $data_to_save = array(
                'post_id' => $post_id,
                'set_id'  => $set_id
            );

            yasr_setcookie($cookiename, $data_to_save);

            $rating_saved_text = __('Rating Saved', 'yet-another-stars-rating');
            $rating_saved_text = wp_kses_post(apply_filters('yasr_mv_saved_text', $rating_saved_text));

            return array(
                'status'    => 'success',
                'text'      => $rating_saved_text
            );
        }

        return array(
            'status' => 'error',
            'error'  => __('Rating not saved. Please Try again', 'yet-another-stars-rating')
        );

    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     */
    public function returnVVStats() {
        if (isset($_POST['post_id']) && $_POST['post_id'] !== '') {
            $post_id = (int)$_POST['post_id'];
        }
        else {
            die('Missing post ID');
        }

        $votes_array  = YasrDatabaseRatings::getVisitorVotes($post_id);
        $votes_number = $votes_array['number_of_votes'];

        if ($votes_number !== 0) {
            $medium_rating = ($votes_array['sum_votes'] / $votes_number);
        }
        else {
            $medium_rating = 0;
        }

        $medium_rating = round($medium_rating, 1);
        $missing_vote  = null; //avoid undefined variable

        global $wpdb;

        //create an empty array
        $existing_votes = array();

        $stats = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ROUND(vote, 0) as vote, 
                       COUNT(vote)    as n_of_votes
                FROM " . YASR_LOG_TABLE . "
                    WHERE post_id=%d
                    AND   vote > 0 
                    AND   vote <= 5
                GROUP BY vote
                ORDER BY vote DESC
                ", $post_id
            ), ARRAY_A
        );

        $total_votes = 0; //Avoid undefined variable if stats exists. Necessary if $stats not exists

        //if query return 0 write an empty array $existing_votes
        if ($stats) {
            //Write a new array with only existing votes, and count all the number of votes
            foreach ($stats as $votes_array) {
                $existing_votes[] = $votes_array['vote'];//Create an array with only existing votes
                $total_votes      = $total_votes + $votes_array['n_of_votes'];
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            //If query return 0 write a new $stats array with index
            if (!$stats) {
                $stats[$i]               = array();
                $stats[$i]['vote']       = $i;
                $stats[$i]['n_of_votes'] = 0;
            }
            else {
                //If in the new array there are some vote missing create a new array
                /** @noinspection TypeUnsafeArraySearchInspection */
                if (!in_array($i, $existing_votes)) {
                    $missing_vote[$i]               = array();
                    $missing_vote[$i]['vote']       = $i;
                    $missing_vote[$i]['n_of_votes'] = 0;
                }
            }
        }

        //If missing_vote exists merge it
        if ($missing_vote) {
            $stats = array_merge($stats, $missing_vote);
        }

        arsort($stats); //sort it by $votes[n_of_votes]

        if ($total_votes === 0) {
            $increase_bar_value = 0;
        }
        else {
            $increase_bar_value = 100 / $total_votes; //Find how much all the bars should increase per vote
        }

        $i = 5;

        $array_to_return = array(
            'medium_rating' => $medium_rating
        );

        foreach ($stats as $logged_votes) {
            //cast int
            $logged_votes['n_of_votes'] = (int)$logged_votes['n_of_votes'];

            $value_progressbar = $increase_bar_value * $logged_votes['n_of_votes']; //value of the single bar
            $value_progressbar = round($value_progressbar, 2) . '%'; //use only 2 decimal

            $array_to_return[$i]['progressbar'] = $value_progressbar;
            $array_to_return[$i]['n_of_votes']  = $logged_votes['n_of_votes'];
            $array_to_return[$i]['vote']        = $logged_votes['vote'];

            $i--;

            //if there is a 0 rating in the database (only possible if manually added) break foreach
            if ($i < 1) {
                break;
            }
        } //End foreach

        echo json_encode($array_to_return);

        die();
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since        2.8.0
     * @param        $nonce
     * @param        $action_name
     * @param string $error
     * @return string|bool;
     */
    public static function validNonce($nonce, $action_name, $error=false) {
        if (!wp_verify_nonce($nonce, $action_name)) {
            if(!$error) {
                $error = __('Wrong nonce. Rating can\'t be updated', 'yet-another-stars-rating');
            } else {
                $error = sanitize_text_field($error);
            }
            $error_nonce = array(
                'status' => 'error',
                'text'  => $error
            );
            return json_encode($error_nonce);
        }
        return true;
    }


    /**
     * This function returns ranking data for both rest and ajax requests
     *
     * @author Dario Curvino <@dudo>
     * @since 2.7.9
     * @param bool|string $source
     * @param bool|array  $request
     *
     * @return array|false|false[]
     */
    public static function rankingData($source=false, $request=false) {
        $is_ajax = false;

        if (isset($_GET['action']) && isset($_GET['source']) && isset($_GET['nonce_rankings'])) {
            $request = $_GET;
            $source  = (string)$_GET['source'];
            $is_ajax = true;
        }

        $data_to_return = array(
            'source' => $source
        );

        //hook here to add more params
        $sql_params = apply_filters('yasr_filter_ranking_request', false, $request);

        if($source === 'overall_rating') {
            $overall_data = YasrRankingData::rankingOverallGetResults($sql_params);
            if($overall_data === false){
                $data_to_return = false;
            }
            else {
                $data_to_return['data_overall'] = YasrRankings::rankingData($overall_data);
            }
        }

        if($source === 'visitor_votes') {
            //outside 'most', only 'highest' is allowed
            $ranking                = ($request['show'] === 'highest') ? $request['show'] : 'most';
            $data_to_return['show'] = $ranking;

            $vv_data = YasrRankingData::rankingVVGetResults($sql_params, $ranking);
            if ($vv_data === false) {
                $data_to_return = false;
            }
            else {
                $data_to_return['data_vv'] = YasrRankings::rankingData($vv_data);
            }
        }

        if($source === 'author_multi') {
            $am_data = YasrRankingData::rankingMulti($request['setid'], $sql_params);
            if($am_data === false){
                $data_to_return = false;
            }
            else {
                $data_to_return['data_mv'] = YasrRankings::rankingData($am_data);
            }
        }

        if($source === 'visitor_multi') {
            //outside 'most', only 'highest' is allowed
            $ranking                = ($request['show'] === 'highest') ? $request['show'] : 'most';
            $data_to_return['show'] = $ranking;

            $vm_data = YasrRankingData::rankingMultiVV($request['setid'], $ranking, $sql_params);
            if($vm_data === false){
                $data_to_return = false;
            }
            else {
                $data_to_return['data_vv'] = YasrRankings::rankingData($vm_data);
            }
        }

        //Use this hook to works with more $sources
        $data_to_return = apply_filters('yasr_add_sources_ranking_request', $data_to_return, $source, $request, $sql_params);

        //if this is coming from an ajax request
        if($is_ajax === true) {
            echo json_encode($data_to_return);
            die();
        }

        return $data_to_return;

    }
}