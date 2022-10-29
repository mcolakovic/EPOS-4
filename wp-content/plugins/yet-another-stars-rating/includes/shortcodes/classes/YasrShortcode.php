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
if ( !defined( 'ABSPATH' ) ) {
    exit( 'You\'re not allowed to see this page' );
}
// Exit if accessed directly
/**
 * Class YasrShortcode
 *
 * @since 2.1.5
 *
 */
abstract class YasrShortcode
{
    public  $shortcode_html ;
    public  $post_id ;
    //false
    public  $size ;
    //large
    public  $readonly ;
    //false
    public  $set_id ;
    //1
    public  $show_average ;
    //null
    public  $shortcode_name ;
    public function __construct( $atts, $shortcode_name )
    {
        //Enqueue Scripts
        self::enqueueScripts();
        $this->shortcode_name = $shortcode_name;
        $this->initProperties( $atts, $shortcode_name );
    }
    
    protected function initProperties( $atts, $shortcode_name )
    {
        
        if ( $atts !== false ) {
            $atts = shortcode_atts( array(
                'size'         => 'large',
                'postid'       => false,
                'readonly'     => false,
                'setid'        => YASR_FIRST_SETID,
                'show_average' => null,
            ), $atts, $shortcode_name );
            
            if ( $atts['postid'] === false ) {
                $this->post_id = get_the_ID();
            } else {
                $this->post_id = (int) $atts['postid'];
            }
            
            $this->size = sanitize_text_field( $atts['size'] );
            $this->readonly = sanitize_text_field( $atts['readonly'] );
            $this->set_id = (int) $atts['setid'];
            $this->show_average = sanitize_text_field( $atts['show_average'] );
        }
    
    }
    
    /**
     * Return the stars size according to size attribute in shortcode.
     * If not used, return 32 (default value)
     *
     * @return int
     */
    protected function starSize()
    {
        $size = null;
        if ( $this->shortcode_name === 'yasr_ov_ranking' || $this->shortcode_name === 'yasr_most_or_highest_rated_posts' || $this->shortcode_name === 'yasr_pro_ur_ranking' || $this->shortcode_name === 'yasr_multi_set_ranking' || $this->shortcode_name === 'yasr_visitor_multi_set_ranking' ) {
            //default size for all rankings
            $size = 'medium';
        }
        if ( $size === null ) {
            $size = $this->size;
        }
        $px_size = 32;
        //default value
        
        if ( $size === 'small' ) {
            $px_size = 16;
        } elseif ( $size === 'medium' ) {
            $px_size = 24;
        }
        
        return $px_size;
    }
    
    /**
     * Enable or disable stars, works for both VisitorVotes and VisitorMultiSet
     *
     * @param $cookie_value
     *
     * @return string|bool;
     */
    public static function starsEnalbed( $cookie_value )
    {
        $is_user_logged_in = is_user_logged_in();
        //Logged in user is always able to vote
        if ( $is_user_logged_in === true ) {
            return 'true_logged';
        }
        //If only logged in users can vote
        if ( YASR_ALLOWED_USER === 'logged_only' ) {
            //IF user is not logged in
            if ( $is_user_logged_in === false ) {
                return 'false_not_logged';
            }
        }
        //if anonymous are allowed to vote
        if ( YASR_ALLOWED_USER === 'allow_anonymous' ) {
            //I've to check if is user is not logged in
            
            if ( $is_user_logged_in === false ) {
                //if cookie !== false means that exists, and user can't vote
                if ( $cookie_value !== false ) {
                    return 'false_already_voted';
                }
                return 'true_not_logged';
            }
        
        }
        //end if YASR_ALLOWED_USER === 'allow_anonymous'
        //this should never happen
        return false;
    }
    
    /**
     * Enqueue js scripts
     * Js files for vv shortcodes are loaded with method in his own class
     *
     * @author Dario Curvino <@dudo>
     * @since  2.8.5
     */
    public static function enqueueScripts()
    {
        //scripts required for all shortocdes
        yasr_enqueue_includes_js_scripts();
        do_action( 'yasr_enqueue_assets_shortcode' );
    }
    
    /***
     * This function is called by all the shortcodes that works in read only and in multiset
     *
     * @author Dario Curvino <@dudo>
     * @since  2.8.8
     */
    public static function enqueueReadonlyAndMultisetScript()
    {
        wp_enqueue_script(
            'yasr-ov-multi',
            YASR_JS_DIR_INCLUDES . 'shortcodes/overall-multiset.js',
            array(
            'jquery',
            'rater',
            'wp-i18n',
            'yasr-global-data'
        ),
            YASR_VERSION_NUM,
            true
        );
    }
    
    /**
     * Enqueue scripts for rankings
     *
     * @author Dario Curvino <@dudo>
     * @since  2.8.8
     */
    public static function enqueueRankingScript()
    {
        wp_enqueue_script(
            'yasr-rankings',
            YASR_JS_DIR_INCLUDES . 'shortcodes/rankings.js',
            array(
            'jquery',
            'rater',
            'wp-i18n',
            'wp-element',
            'yasr-global-data'
        ),
            YASR_VERSION_NUM,
            true
        );
    }

}