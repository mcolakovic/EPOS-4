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

if (!defined( 'ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

$multi_set = YasrMultiSetData::returnMultiSetNames();
$post_id = get_the_ID();
$yasr_nonce_multi = wp_nonce_field( "yasr_nonce_save_multi_values_action", "yasr_nonce_save_multi_values");

$set_id = NULL;

global $wpdb;

$n_multi_set = $wpdb->num_rows; //wpdb->num_rows always store the the count number of rows of the last query

//this is always the first set id
$set_id = $multi_set[0]->set_id;
$set_id = (int)$set_id;

if ($n_multi_set > 1) {
    ?>
    <div>
        <?php esc_html_e("Choose which set you want to use", 'yet-another-stars-rating'); ?>
        <br />
        <label for="select_set">
            <select id="select_set" autocomplete="off">
                <?php
                    foreach ($multi_set as $name) {
                        echo "<option value='".esc_attr($name->set_id)."'>".esc_attr($name->set_name)."</option>";
                    } //End foreach
                ?>
            </select>
        </label>

        <button href="#" class="button-delete" id="yasr-button-select-set"><?php esc_html_e("Select"); ?></button>

        <span id="yasr-loader-select-multi-set" style="display:none;" >&nbsp;
            <img src="<?php echo esc_url(YASR_IMG_DIR . "/loader.gif") ?>" alt="yasr-loader">
        </span>
    </div>

    <?php 

} //End if if ($n_multi_set>1)

?>

<div id="yasr-editor-multiset-container"
     data-nmultiset="<?php echo esc_attr($n_multi_set) ?>"
     data-setid="<?php echo esc_attr($set_id) ?>"
     data-postid="<?php echo esc_attr($post_id) ?>
">

    <span id="yasr-multi-set-admin-choose-text">
        <?php esc_html_e( 'Choose a vote for each element', 'yet-another-stars-rating' ); ?>
    </span>

    <input type="hidden" name="yasr_multiset_author_votes" id="yasr-multiset-author-votes" value="">
    <input type="hidden" name="yasr_multiset_id" id="yasr-multiset-id" value="">

    <table class="yasr_table_multi_set_admin" id="yasr-table-multi-set-admin">

    </table>

    <div id="yasr-multi-set-admin-explain">
        <?php esc_html_e( "If you want to insert this multiset, paste this shortcode", 'yet-another-stars-rating' ); ?>
        <span id="yasr-multi-set-admin-explain-with-id-readonly"></span>. <br />

        <?php esc_html_e( "If, instead, you want allow your visitor to vote on this multiset, use this shortcode", 'yet-another-stars-rating' ); ?>
        <span id='yasr-multi-set-admin-explain-with-id-visitor'></span>.
        <?php esc_html_e('In this case, you don\'t need to vote here', 'yet-another-stars-rating');?>

        <br />
        <br />

        <?php
            $yasr_pro_string = sprintf(
                    __( "With %sYasr Pro%s you can use %s yasr_pro_average_multiset %s and 
                    %s yasr_pro_average_visitor_multiset %s to print a separate average of the Multi Set.",
                    'yet-another-stars-rating' ),
            '<a href="https://yetanotherstarsrating.com/?utm_source=wp-plugin&utm_medium=metabox_multiple_rating&utm_campaign=yasr_editor_screen&utm_content=yasr-pro#yasr-pro">',
            '</a>',
            '<strong>', '</strong>',
            '<strong>', '</strong>');

            echo wp_kses_post($yasr_pro_string);
        ?>
        <span id='yasr-multi-set-admin-explain-with-id-visitor'></span>

    </div>

</div>
