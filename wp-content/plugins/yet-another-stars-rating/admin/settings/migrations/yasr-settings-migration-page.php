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

$plugin_imported = get_option('yasr_plugin_imported');

?>

<h3><?php esc_html_e('Migration Tools', 'yet-another-stars-rating'); ?></h3>

<table class="form-table yasr-settings-table" id="yasr-migrate-table">
    <tr>
        <td>
            <div>
            <?php
                $rating_plugin_exists = new YasrImportRatingPlugins;

                if (!$rating_plugin_exists->yasr_search_wppr() && !$rating_plugin_exists->yasr_search_rmp()
                    && !$rating_plugin_exists->yasr_search_kksr() && !$rating_plugin_exists->yasr_search_mr()) {
                    ?>
                    <span class="title-noplugin-found">
                        <?php esc_html_e('No supported plugin has been found' , 'yet-another-stars-rating'); ?>
                    </span>
                    <?php
                }

                if($rating_plugin_exists->yasr_search_wppr()){
                    $nonce_import_wppr = wp_create_nonce('yasr-import-wppr-action');
                    ?>
                    <span class="title-plugin-found">
                        <?php esc_html_e('Plugin found: WP-PostRatings' , 'yet-another-stars-rating'); ?>
                    </span>
                    <?php

                    $number_of_stars = (int)get_option('postratings_max', false);

                    if ($number_of_stars && $number_of_stars !== 5) {
                        $error  = '<div class="yasr-indented-answer" style="margin-top: 10px;">';
                        $error .= sprintf(__('You\' re using a star set different from 5 %s
                            Import can not be done', 'yet-another-stars-rating'), '<br />');
                        $error .= '</div>';
                        echo wp_kses_post($error);
                    } else {
                        $info  = '<div class="yasr-indented-answer" style="margin-top: 10px;">';
                        $info .= sprintf(__(
                            '%s Please note: %s depending on the settings, Wp-PostRatings may save data in different ways. %s 
                        The only way to be sure to get ALL data is, for every single post or page, getting the total 
                        number of votes, and save the current average as the rating for all votes. %s
                        E.g. A post has 130 votes with an average of 4.4: since is impossible to know the single rating,
                        Yasr will import 130 votes with an average of 4.4. %s
                        Because of this, statistics in front end will be disabled for all post or page published before 
                        the import.',
                            'yet-another-stars-rating'
                        ), '<strong>', '</strong>', '<br />', '<br />', '<br />');
                        $info.='</div>';

                        echo wp_kses_post($info);

                        if (is_array($plugin_imported) && array_key_exists('wppr', $plugin_imported)) {
                            echo wp_kses_post('<div class="yasr-indented-answer" style="margin-top: 10px;">'
                                 . __('You\'ve already imported WP-PostRatings data on', 'yet-another-stars-rating') .
                                 '&nbsp;<strong>' . $plugin_imported['wppr']['date'] . '</strong>
                              </div>');
                        } else {

                            $number_of_queries_wppr = (int) $rating_plugin_exists->yasr_count_wppr_query_number();

                            if ($number_of_queries_wppr > 1000) {
                                yasr_import_plugin_alert_box('WP-PostRatings', $number_of_queries_wppr);
                            }

                            ?>
                            <div class="yasr-indented-answer">
                                <button class="button-primary" id="yasr-import-wppr-submit">
                                    <?php esc_html_e('Import data', 'yet-another-stars-rating') ?>
                                </button>
                                <input type="hidden" id="yasr-import-wppr-nonce"
                                       value="<?php echo esc_attr($nonce_import_wppr) ?>">
                            </div>
                            <div id="yasr-import-wppr-answer" class="yasr-indented-answer">
                            </div>

                            <div class="yasr-space-settings-div">
                            </div>

                            <?php
                        }

                    }
                    ?>

                    <?php
                }

                if($rating_plugin_exists->yasr_search_kksr()){
                    $nonce_import_kksr = wp_create_nonce('yasr-import-kksr-action');
                    ?>
                    <span class="title-plugin-found">
                         <?php esc_html_e('Plugin found: KK Star Ratings' , 'yet-another-stars-rating'); ?>
                    </span>
                    <?php

                    echo '<div class="yasr-indented-answer" style="margin-top: 10px;">';
                    echo sprintf(__(
                        '%s Please note: %s KK Star Ratings doesn\'t save information about the single vote. %s 
                            The only way to be sure to get ALL data is, for every single post or page, getting the total 
                            number of votes, and save the current average as the rating for all votes. %s
                            E.g. A post has 130 votes with an average of 4.4: since is impossible to know the single rating,
                            Yasr will import 130 votes with an average of 4.4 %s
                            Because of this, statistics in front end will be disabled for all post or page published before 
                            the import.%s
                            If you use a rating scale different than 1 to 5, all ratings will be converted to work with a 
                            5 ratings star scale.',
                        'yet-another-stars-rating'
                    ), '<strong>', '</strong>', '<br />', '<br />', '<br />', '<br />');
                    echo '</div>';

                    if (is_array($plugin_imported) && array_key_exists('kksr', $plugin_imported)) {
                        echo '<div class="yasr-indented-answer" style="margin-top: 10px;">'
                             .__('You\'ve already imported KK Star Rating data on', 'yet-another-stars-rating').
                             '&nbsp;<strong>'.$plugin_imported['kksr']['date']. '</strong>
                                  </div>';
                    } else {

                        $number_of_queries_kksr = (int)$rating_plugin_exists->yasr_count_kksr_query_number();

                        if($number_of_queries_kksr > 1000) {
                            yasr_import_plugin_alert_box ('KK Stars Rating', $number_of_queries_kksr);
                        }

                        ?>
                        <div class="yasr-indented-answer">
                            <button class="button-primary" id="yasr-import-kksr-submit">
                                <?php esc_html_e('Import data', 'yet-another-stars-rating') ?>
                            </button>
                            <input type="hidden" id="yasr-import-kksr-nonce" value="<?php echo $nonce_import_kksr ?>">
                        </div>
                        <div id="yasr-import-kksr-answer" class="yasr-indented-answer">
                        </div>

                        <div class="yasr-space-settings-div">
                        </div>

                        <?php
                    }
                    ?>

                    <?php
                }

                if($rating_plugin_exists->yasr_search_rmp()) {
                    $nonce_import_rmp = wp_create_nonce('yasr-import-ratemypost-action');
                    ?>
                        <span class="title-plugin-found">
                            <?php esc_html_e('Plugin found: Rate My Post' , 'yet-another-stars-rating'); ?>
                        </span>
                        <?php
                            if  (is_array($plugin_imported) && array_key_exists('rmp', $plugin_imported)) {
                                echo '<div class="yasr-indented-answer">'
                                     .__('You\'ve already imported Rate My Post data on', 'yet-another-stars-rating').
                                     '&nbsp;<strong>'.$plugin_imported['rmp']['date']. '</strong>
                                     </div>';
                            } else {
                                $number_of_queries_rmp = (int)$rating_plugin_exists->yasr_count_rmp_query_number();

                                if($number_of_queries_rmp > 1000) {
                                    yasr_import_plugin_alert_box ('Rate My Post', $number_of_queries_rmp);
                                }
                                ?>
                                    <div class="yasr-indented-answer">
                                        <button class="button-primary" id="yasr-import-ratemypost-submit">
                                            <?php esc_html_e('Import data', 'yet-another-stars-rating') ?>
                                        </button>
                                        <input type="hidden" id="yasr-import-rmp-nonce" value="<?php echo $nonce_import_rmp ?>">
                                    </div>
                                    <div id="yasr-import-ratemypost-answer" class="yasr-indented-answer">
                                    </div>
                                <?php
                            }
                        ?>

                    <?php
                }

                if($rating_plugin_exists->yasr_search_mr()){
                    $nonce_import_mr = wp_create_nonce('yasr-import-mr-action');
                    ?>
                    <span class="title-plugin-found">
                            <?php esc_html_e('Plugin found: Multi Rating' , 'yet-another-stars-rating'); ?>
                        </span>
                    <?php

                    echo '<div class="yasr-indented-answer" style="margin-top: 10px;">';
                        echo sprintf(__(
                            '%s Please note: %s depending on the settings, Multi Rating may save data in different ways. %s 
                            The only way to be sure to get ALL data is, for every single post or page, getting the total 
                            number of votes, and save the current average as the rating for all votes. %s
                            E.g. A post has 130 votes with an average of 4.4: since is impossible to know the single rating,
                            YASR will import 130 votes with an average of 4.4. %s
                            Because of this, statistics in front end will be disabled for all post or page published before 
                            the import.',
                            'yet-another-stars-rating'
                        ), '<strong>', '</strong>', '<br />', '<br />', '<br />');
                    echo '</div>';

                    if (is_array($plugin_imported) && array_key_exists('mr', $plugin_imported)) {
                        echo '<div class="yasr-indented-answer" style="margin-top: 10px;">'
                             . __('You\'ve already imported Multi Rating data on', 'yet-another-stars-rating') .
                             '&nbsp;<strong>' . $plugin_imported['mr']['date'] . '</strong>
                              </div>';
                    } else {

                        $number_of_queries_mr = (int) $rating_plugin_exists->yasr_count_mr_query_number();

                        if ($number_of_queries_mr > 1000) {
                            yasr_import_plugin_alert_box('Multi Rating', $number_of_queries_mr);
                        }

                        ?>
                        <div class="yasr-indented-answer">
                            <button class="button-primary" id="yasr-import-mr-submit">
                                <?php esc_html_e('Import data', 'yet-another-stars-rating') ?>
                            </button>
                            <input type="hidden" id="yasr-import-mr-nonce"
                                   value="<?php echo $nonce_import_mr ?>">
                        </div>
                        <div id="yasr-import-mr-answer" class="yasr-indented-answer">
                        </div>

                        <div class="yasr-space-settings-div">
                        </div>

                        <?php
                    }
                }

                do_action('yasr_migration_page_bottom', $plugin_imported);
            ?>
            </div>
        </td>
    </tr>

    <!--Most or highest rated chart-->

</table>