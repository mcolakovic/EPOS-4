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

global $post;

$post_id=get_the_ID();

//equivalent to get_post_meta
$overall_rating = $post->yasr_overall_rating;

wp_nonce_field('yasr_nonce_overall_rating_action', 'yasr_nonce_overall_rating');
wp_nonce_field('yasr_nonce_auto_insert_action', 'yasr_nonce_auto_insert');
wp_nonce_field('yasr_nonce_review_type_action', 'yasr_nonce_review_type');
wp_nonce_field('yasr_nonce_is_post_review_action', 'yasr_nonce_is_post_review');

?>

<div id="yasr-matabox-top-right">

    <input type='hidden'
           name='yasr_overall_rating'
           id='yasr-overall-rating-value'
           value='<?php echo esc_attr($overall_rating);?>'>
        <div id="yasr-vote-overall-stars-container">
            <div id="yasr-vote-overall-stars">
                <span id="yasr-overall-rating-text">
                    <?php esc_html_e('Rate this article / item', 'yet-another-stars-rating'); ?>
                </span>

                <div id="yasr-rater-overall" >
                </div>

                <p />

                <div>
                    <span id="yasr_overall_text"></span>
                </div>

            </div>
        </div> <!--End stars container-->
        <div>
            <?php
            //Show this message if auto insert is off or if auto insert is not set to show overall rating (so if it is set to visitor rating)
            if( YASR_AUTO_INSERT_ENABLED === 0 || (YASR_AUTO_INSERT_ENABLED === 1 && YASR_AUTO_INSERT_WHAT === 'visitor_rating') ) {
                echo "<div>";
                printf(esc_html__ ('Remember to insert this shortcode %s[yasr_overall_rating]%s
                             where you want to display this rating.','yet-another-stars-rating'), '<strong>', '</strong>');
                echo "</div>";
            }
            ?>
        </div>

    <?php

        if (YASR_AUTO_INSERT_ENABLED === 1) {
            $is_this_post_exluded = get_post_meta($post_id, 'yasr_auto_insert_disabled', true);
            ?>

            <hr>
            <div id="yasr-toprightmetabox-disable-auto-insert">
                <?php esc_html_e('Disable auto insert for this post or page?', 'yet-another-stars-rating'); ?>
                    <br />
                    <div class="yasr-onoffswitch-big yasr-onoffswitch-big-center" id="yasr-switcher-disable-auto-insert">
                        <input type="checkbox"
                               name="yasr_auto_insert_disabled"
                               class="yasr-onoffswitch-checkbox"
                               value="yes"
                               id="yasr-auto-insert-disabled-switch"
                               <?php if ($is_this_post_exluded === 'yes') {echo " checked='checked' ";} ?>
                        >
                        <label class="yasr-onoffswitch-label" for="yasr-auto-insert-disabled-switch">
                            <span class="yasr-onoffswitch-inner"></span>
                            <span class="yasr-onoffswitch-switch"></span>
                        </label>
                    </div>
            </div>

        <?php

    } //End if auto insert enabled

    ?>
    <p>
    <?php
        do_action( 'yasr_add_content_bottom_topright_metabox', $post_id );
    ?>

</div>