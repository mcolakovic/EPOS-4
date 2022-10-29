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

if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'yet-another-stars-rating'));
}

if (isset($_GET['tab'])) {
    $active_tab = $_GET['tab'];
} else {
    $active_tab = 'logs';
}

?>

<div class="wrap">
    <h2>Yet Another Stars Rating: <?php esc_html_e("Ratings Stats", 'yet-another-stars-rating'); ?></h2>

    <h2 class="nav-tab-wrapper yasr-no-underline">

        <a href="?page=yasr_stats_page&tab=logs" class="nav-tab
            <?php echo ($active_tab === 'logs') ? 'nav-tab-active' : ''; ?>"
            >
            <?php esc_html_e("Visitor Votes", 'yet-another-stars-rating'); ?>
        </a>

        <a href="?page=yasr_stats_page&tab=logs_multi" class="nav-tab
            <?php echo ($active_tab === 'logs_multi') ? 'nav-tab-active' : ''; ?>"
        >
            <?php esc_html_e("MultiSet", 'yet-another-stars-rating'); ?>
        </a>

        <a href="?page=yasr_stats_page&tab=overall" class="nav-tab
            <?php echo ($active_tab === 'overall') ? 'nav-tab-active' : ''; ?>"
        >
            <?php esc_html_e("Overall Rating", 'yet-another-stars-rating'); ?>
        </a>

        <?php
            //Use this hooks to add tabs in the stats page
            do_action('yasr_add_stats_tab', $active_tab);
        ?>

        <a href="?page=yasr_settings_page-pricing" class="nav-tab">
            <?php esc_html_e("Upgrade", 'yet-another-stars-rating'); ?>
        </a>

    </h2>

    <?php

    if ($active_tab === 'logs' || $active_tab === '') {
        ?>

        <div class="yasr-settingsdiv yasr-settings-table">
            <div class="yasr-settings-table">
                <form action="#" id="" method="POST">
                    <?php
                        wp_nonce_field('yasr-delete-stats-logs', 'yasr-nonce-delete-stats-logs');
                        $yasr_stats_log_table = new YasrStats($active_tab);
                        $yasr_stats_log_table->prepare_items();
                        $yasr_stats_log_table->display();
                    ?>
                </form>
            </div>
        </div>

        <?php

    } //End if tab 'logs'

    if ($active_tab === 'logs_multi') {
        ?>
        <div class="yasr-settingsdiv yasr-settings-table">
            <div class="yasr-settings-table">
                <form action="#" id="" method="POST">
                    <?php
                        wp_nonce_field('yasr-delete-stats-logs', 'yasr-nonce-delete-stats-logs');
                        $yasr_stats_log_table = new YasrStats($active_tab);
                        $yasr_stats_log_table->prepare_items();
                        $yasr_stats_log_table->display();
                    ?>
                </form>
            </div>
        </div>
        <?php

    } //End if tab 'general_settings'

    if ($active_tab === 'overall') {
        ?>
        <div class="yasr-settingsdiv">
            <div class="yasr-settings-table">
                <form action="#" id="" method="POST">
                    <?php
                    wp_nonce_field('yasr-delete-stats-logs', 'yasr-nonce-delete-stats-logs');
                    $yasr_stats_log_table = new YasrStats($active_tab);
                    $yasr_stats_log_table->prepare_items();
                    $yasr_stats_log_table->display();
                    ?>
                </form>
            </div>
        </div>
        <?php

    } //End if tab 'overall'

    do_action('yasr_settings_check_active_tab', $active_tab);
    ?>

    <div class="yasr-clear-both-dynamic"></div>

    <?php yasr_right_settings_panel(); ?>

</div><!--End div wrap-->