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

?>

<div class="wrap">
    <h2>Yet Another Stars Rating: <?php esc_html_e('Settings', 'yet-another-stars-rating'); ?></h2>
    <?php
        if (isset($_GET['tab'])) {
            $active_tab = $_GET['tab'];
        } else {
            $active_tab = 'general_settings';
        }

        //Do the settings tab
        yasr_settings_tabs($active_tab);
        ?>
            <div class="yasr-settingsdiv">
                <div class="yasr-settings-table">
                <?php

                if ($active_tab === 'general_settings') {
                    ?>
                    <form action="options.php" method="post" id="yasr_settings_form">
                        <?php
                            settings_fields('yasr_general_options_group');
                            do_settings_sections('yasr_general_settings_tab');
                            submit_button(YASR_SAVE_All_SETTINGS_TEXT);
                        ?>
                    </form>
                    <?php

                } //End if tab 'general_settings'

                if ($active_tab === 'manage_multi') {
                    include(YASR_ABSOLUTE_PATH_ADMIN . '/settings/multiset/yasr-settings-functions-multiset-page.php');
                } //End if ($active_tab=='manage_multi')

                if ($active_tab === 'style_options') {
                    ?>
                    <form action="options.php" method="post" enctype='multipart/form-data' id="yasr_settings_form">
                        <?php
                        settings_fields('yasr_style_options_group');
                        do_settings_sections('yasr_style_tab');
                        submit_button(YASR_SAVE_All_SETTINGS_TEXT);
                        ?>
                    </form>
                    <?php

                } //End tab style

                if ($active_tab === 'rankings') {
                    include(YASR_ABSOLUTE_PATH_ADMIN . '/settings/rankings/yasr-ranking-builder.php');
                } //End tab ur options

                if ($active_tab === 'migration_tools') {
                    //include migration functions
                    include(YASR_ABSOLUTE_PATH_ADMIN . '/settings/migrations/yasr-settings-migration-page.php');
                } //End tab migration

                //Adds new tab content here
                do_action('yasr_settings_tab_content', $active_tab);

                ?>

                </div> <!--End yasr-settingsdiv-->
            </div>

    <div class="yasr-clear-both-dynamic"></div>
    <?php
        yasr_right_settings_panel();
    ?>
    <!--End div wrap-->
</div>