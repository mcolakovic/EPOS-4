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
 * Class YasrVisitorVotes
 * Print Yasr Visitor Votes
 */
class YasrVisitorVotes extends YasrShortcode {

    protected  $is_singular;
    protected  $unique_id;
    protected  $ajax_nonce_visitor;
    protected static $tippy_loaded;

    public function __construct($atts, $shortcode_name) {
        parent::__construct($atts, $shortcode_name);

        if (is_singular()) {
            $this->is_singular = 'true';
        } else {
            $this->is_singular = 'false';
        }

        $this->unique_id          = str_shuffle(uniqid());
        $this->ajax_nonce_visitor = wp_create_nonce("yasr_nonce_vv");

        self::$tippy_loaded = false;
    }

    /**
     * Print the visitor votes shortcode
     *
     * @return string|null
     */
    public function returnShortcode() {
        $stored_votes    = YasrDatabaseRatings::getVisitorVotes($this->post_id);
        $number_of_votes = $stored_votes['number_of_votes'];

        if ($number_of_votes > 0) {
            $average_rating = $stored_votes['sum_votes']/$number_of_votes;
        } else {
            $average_rating = 0;
        }

        $average_rating=round($average_rating, 1);

        self::defineVvAttributes($stored_votes);

        //if this come from yasr_visitor_votes_readonly...
        if ($this->readonly === 'true' || $this->readonly === "yes") {
            return $this->readonlyShortcode($average_rating, $stored_votes);
        }

        $cookie_value  = self::checkCookie($this->post_id);
        $stars_enabled = YasrShortcode::starsEnalbed($cookie_value);

        if($stars_enabled === 'true_logged' || $stars_enabled === 'true_not_logged') {
            $this->readonly = 'false'; //Always false if user is logged in
        } else {
            $this->readonly = 'true';
        }

        return $this->returnYasrVisitorVotes($stored_votes, $number_of_votes, $average_rating, $cookie_value);

    } //end function

    /**
     * Load visitorVotes.js if needed
     *
     * @author Dario Curvino <@dudo>
     * @since 2.8.5
     */
    public function loadVVScripts() {
        $array_dep = array('jquery', 'rater', 'wp-i18n', 'yasr-global-data');

        if(self::$tippy_loaded === true) {
            $array_dep[] = 'tippy';
        }

        wp_enqueue_script(
            'yasr-front-vv',
            YASR_JS_DIR_INCLUDES . 'shortcodes/visitorVotes.js',
            $array_dep,
            YASR_VERSION_NUM,
            true
        );
    }

    /**
     * Load tippy if needed
     *
     * @author Dario Curvino <@dudo>
     * @since 2.8.5
     */
    public function loadTippy() {
        wp_enqueue_script(
            'tippy',
            YASR_JS_DIR_INCLUDES . 'tippy.all.min.js',
            '',
            '3.6.0',
            true
        );

        self::$tippy_loaded = true;
    }

    /**
     * Return YASR VV in read only
     *
     * @author Dario Curvino <@dudo>
     * @since 2.7.4
     * @param $average_rating
     * @param $stored_votes
     *
     * @return mixed|void
     */
    public function readonlyShortcode ($average_rating, $stored_votes) {
        $container = '<div id="yasr-vv-stars-stats-container-'.$this->unique_id.'">';

        $htmlid = 'yasr-visitor-votes-readonly-rater-'.$this->unique_id;

        $stars = "<div class='yasr-rater-stars'
                      id='$htmlid'
                      data-rating='$average_rating'
                      data-rater-starsize='".$this->starSize()."'
                      data-rater-postid='$this->post_id' 
                      data-rater-readonly='true'
                      data-readonly-attribute='true' 
                      data-rater-nonce='$this->ajax_nonce_visitor'
                  ></div>";

        $end_container = '</div>'; //close yasr-vv-stars-stats-container

        $shortcode_html = $container . $stars . $end_container;

        YasrShortcode::enqueueReadonlyAndMultisetScript();

        //Use this filter to customize yasr_visitor_votes readonly
        return apply_filters('yasr_vv_ro_shortcode', $shortcode_html, $stored_votes, $this->post_id);
    }

    /**
     * Function that checks if cookie exists and set the value
     *
     * @param $post_id int|bool
     * @return int|bool
     */
    public static function checkCookie ($post_id = false) {
        $yasr_cookiename = apply_filters('yasr_vv_cookie', 'yasr_visitor_vote_cookie');

        $cookie_value = false;

        if($post_id === false) {
            $post_id = get_the_ID();
        }

        if (isset($_COOKIE[$yasr_cookiename])) {
            $cookie_data = stripslashes($_COOKIE[$yasr_cookiename]);

            //By default, json_decode return an object, true to return an array
            $cookie_data = json_decode($cookie_data, true);

            if (is_array($cookie_data)) {
                foreach ($cookie_data as $value) {
                    $cookie_post_id = (int)$value['post_id'];
                    if ($cookie_post_id === $post_id) {
                        $cookie_value = (int)$value['rating'];
                        //since version 2.4.0 break is removed, because yasr_setcookie PUSH the value (for logged in users)
                        //so to be sure to get the correct value, I need the last
                    }
                }
            }

            //I've to check $cookie_value !== false before because
            //if $cookie_value is false, $cookie_value < 1 return true (...wtf...)
            if($cookie_value !== false) {
                if ($cookie_value > 5) {
                    $cookie_value = 5;
                } elseif ($cookie_value < 1) {
                    $cookie_value = 1;
                }
            }
            //return int
            return $cookie_value;
        }

        //if cookie is not set (return false)
        return $cookie_value;
    }

    /**
     * This function show default (or custom) text depending if rating is allowed or not
     *
     * @param void
     * @param int|bool $post_id
     *
     * @return int|bool|void
     */
    public static function showTextBelowStars ($cookie_value, $post_id=false) {

        $stars_enabled            = YasrShortcode::starsEnalbed($cookie_value);
        $div_bottom_line          = false;
        $span_bottom_line_content = false;

        if ($stars_enabled === 'true_logged' || $stars_enabled === 'false_already_voted') {
            //default value is false
            $rating = false;
            $span_bottom_line_content  = "<span class='yasr-already-voted-text'>";

            //if it is not false_already_voted means it is true_logged
            if($stars_enabled !== 'false_already_voted') {
                //Check if a logged in user has already rated for this post
                $vote_if_user_already_rated = YasrDatabaseRatings::visitorVotesHasUserVoted($post_id);
                //...and if vote exists, assign it into rating
                if($vote_if_user_already_rated) {
                    $rating = $vote_if_user_already_rated;
                }
            } else {
                $rating = $cookie_value;
            }

            //if rating is not false, show the text after the stars
            if($rating) {
                $custom_text  = wp_kses_post(apply_filters('yasr_cstm_text_already_voted', $rating));
            } else {
                $custom_text  = '';
            }

            $span_bottom_line_content .= $custom_text;
            $span_bottom_line_content .= '</span>';
        }

        //If only logged in users can vote
        elseif ($stars_enabled === 'false_not_logged') {
            $span_bottom_line_content  = "<span class='yasr-visitor-votes-must-sign-in'>";
            $span_bottom_line_content .= wp_kses_post(htmlspecialchars_decode(apply_filters('yasr_must_sign_in', '')));
            //if custom text is defined
            $span_bottom_line_content .= '</span>';
        }

        if($span_bottom_line_content !== false) {
            $div_bottom_line  = "<div class='yasr-small-block-bold'>";
            $div_bottom_line .= $span_bottom_line_content;
            $div_bottom_line .= '</div>';
        }

        return $div_bottom_line;
    }


    /**
     * @since 2.4.7
     *
     * Returns text before the stars
     *
     * @param $number_of_votes
     * @param $average_rating
     *
     * @return string
     */
    protected function textBeforeStars($number_of_votes, $average_rating) {
        $custom_text_before_star = apply_filters('yasr_cstm_text_before_vv', $number_of_votes, $average_rating, $this->unique_id);
        $class_text_before       = 'yasr-custom-text-vv-before yasr-custom-text-vv-before-'.$this->post_id;

        return '<div class="'.$class_text_before.'">'
                   . wp_kses_post(htmlspecialchars_decode($custom_text_before_star)) .
               '</div>';
    }


    /**
     * Returns container after stars
     *
     * @since 2.4.7
     *
     * @param $number_of_votes
     * @param $average_rating
     *
     * @return string $span_text_after_stars
     */
    public function containerAfterStars ($number_of_votes, $average_rating) {
        $container_div = '<div class="yasr-vv-stats-text-container" id="yasr-vv-stats-text-container-'. $this->unique_id .'">';

        if (YASR_VISITORS_STATS === 'yes') {
            $container_div .= $this->visitorStats();
        }

        $container_div .= $this->textAfterStars($number_of_votes, $average_rating);

        $container_div .= '</div>';

        return $container_div;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since 2.4.7
     *
     * @param $number_of_votes
     * @param $average_rating
     *
     * @return string
     */
    protected function textAfterStars($number_of_votes, $average_rating) {
        $custom_text  = apply_filters('yasr_cstm_text_after_vv', $number_of_votes, $average_rating, $this->unique_id);
        return wp_kses_post(htmlspecialchars_decode($custom_text));
    }


    /**
     * This function will return the html code for the stat icon
     *
     * @param void
     *
     * @return string
     */
    public function visitorStats () {
        if ($this->externalPluginImported()) {
            $stat_icon = '';
        }
        else {
            $stat_icon = '<svg xmlns="https://www.w3.org/2000/svg" width="20" height="20" 
                                   class="yasr-dashicons-visitor-stats"
                                   data-postid="'.$this->post_id.'" 
                                   id="yasr-stats-dashicon-'.$this->unique_id.'">
                                   <path d="M18 18v-16h-4v16h4zM12 18v-11h-4v11h4zM6 18v-8h-4v8h4z"></path>
                               </svg>';
            $this->loadTippy();
        }

        return $stat_icon;
    }

    /**
     * Check if an external plugin was imported, and if the date of the import is prior of post date,
     * return true
     *
     * @author Dario Curvino <@dudo>
     * @since  2.8.8
     * @return bool
     */
    protected function externalPluginImported () {
        if (YASR_PLUGIN_IMPORTED !== false && is_array(YASR_PLUGIN_IMPORTED)) {
            $plugin_import_date = null; //avoid undefined
            if (array_key_exists('wppr', YASR_PLUGIN_IMPORTED)) {
                $plugin_import_date = YASR_PLUGIN_IMPORTED['wppr']['date'];
            }

            if (array_key_exists('kksr', YASR_PLUGIN_IMPORTED)) {
                $plugin_import_date = YASR_PLUGIN_IMPORTED['kksr']['date'];
            }

            if (array_key_exists('mr', YASR_PLUGIN_IMPORTED)) {
                $plugin_import_date = YASR_PLUGIN_IMPORTED['mr']['date'];
            }

            //remove hour from date
            $plugin_import_date=strtok($plugin_import_date,' ');
            $post_date = get_the_date('Y-m-d', $this->post_id);

            //return true if post_date is < plugin_import_date, return false otherwise
            return $post_date < $plugin_import_date;
        } //End if YASR_PLUGIN_IMPORTED

        return false;
    }

    /***
     *
     *
     * @author Dario Curvino <@dudo>
     * @since  2.8.8
     * @param $stored_votes
     * @param $number_of_votes
     * @param $average_rating
     * @param $cookie_value
     *
     * @return string
     */
    protected function returnYasrVisitorVotes ($stored_votes, $number_of_votes, $average_rating, $cookie_value) {
        $stars_htmlid = 'yasr-visitor-votes-rater-' . $this->unique_id ;

        $shortcode_html  = '<!--Yasr Visitor Votes Shortcode-->';
        $shortcode_html  .= "<div id='yasr_visitor_votes_$this->post_id' class='yasr-visitor-votes'>";
        
        $shortcode_html  .= $this->textBeforeStars($number_of_votes, $average_rating);
        $shortcode_html  .= "<div id='yasr-vv-second-row-container-$this->unique_id' 
                                        class='yasr-vv-second-row-container'>";

        $shortcode_html .= "<div id='$stars_htmlid'
                                      class='yasr-rater-stars-vv'
                                      data-rater-postid='$this->post_id' 
                                      data-rating='$average_rating'
                                      data-rater-starsize='".$this->starSize()."'
                                      data-rater-readonly='$this->readonly'
                                      data-rater-nonce='$this->ajax_nonce_visitor' 
                                      data-issingular='$this->is_singular'
                                    ></div>";

        $shortcode_html .= $this->containerAfterStars($number_of_votes, $average_rating);

        //loader div
        $shortcode_html .= "<div id='yasr-vv-loader-$this->unique_id' class='yasr-vv-container-loader'></div>";

        //close yasr-vv-second-row-container-$this->unique_id'
        $shortcode_html .= '</div>';

        $shortcode_html  = apply_filters('yasr_vv_shortcode', $shortcode_html, $stored_votes);

        $shortcode_html .= $this->bottomContainer($cookie_value, $this->post_id);

        $shortcode_html .= '</div>'; //close all
        $shortcode_html .= '<!--End Yasr Visitor Votes Shortcode-->';

        $this->loadVVScripts();

        return $shortcode_html;
    }

    /**
     * Return Yasr Visitor Votes
     *
     * @param $cookie_value int|bool
     * @param $post_id
     *
     * @return string
     */
    protected function bottomContainer ($cookie_value, $post_id) {
        if(YASR_ENABLE_AJAX === 'yes') {
            $container = "<div id='yasr-vv-bottom-container-$this->unique_id' 
                              class='yasr-vv-bottom-container'
                              style='display:none'>";
        } else {
            $container = "<div id='yasr-vv-bottom-container-$this->unique_id' class='yasr-vv-bottom-container'>";
        }

        //return bottom container
        return $container.self::showTextBelowStars($cookie_value, $post_id).'</div>';
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.8.8
     *
     * @param $stored_votes
     */
    public static function defineVvAttributes($stored_votes) {
        if(!defined('YASR_VV_ATTRIBUTES')) {
            $yasr_vv_attributes =  $stored_votes;
            define('YASR_VV_ATTRIBUTES', json_encode($yasr_vv_attributes));
        }
    }

    /**
     * Returns tippy status
     *
     * @author Dario Curvino <@dudo>
     * @since 2.9.3
     * @return bool
     */
    public static function tippyLoaded() {
        if(self::$tippy_loaded === true) {
            return true;
        }
        return false;
    }
}