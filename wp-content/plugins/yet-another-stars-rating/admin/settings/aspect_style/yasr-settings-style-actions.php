<?php
/*

Copyright 2020 Dario Curvino (email : d.curvino@tiscali.it)

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

//Add stars set for yasr style settings page
//from version 1.2.7
add_action('yasr_style_options_add_settings_field', 'yasr_style_options_add_settings_field_callback');

function yasr_style_options_add_settings_field_callback($style_options) {
    add_settings_field(
        'yasr_style_options_choose_stars_lite',
        __('Choose Stars Set', 'yet-another-stars-rating'),
        'yasr_style_options_choose_stars_lite_callback',
        'yasr_style_tab',
        'yasr_style_options_section_id',
        $style_options
    );
}

function yasr_style_options_choose_stars_lite_callback($style_options) {
    ?>

    <div class='yasr-select-img-container' id='yasr_pro_custom_set_choosen_stars'>
        <div>
            <input type='radio'
                   name='yasr_style_options[stars_set_free]'
                   value='rater'
                   id="radio-img-rater"
                   class='yasr-general-options-scheme-color'
                   <?php if ($style_options['stars_set_free'] === 'rater') {
                       echo " checked=\"checked\" ";
                   } ?> />
            <label for="radio-img-rater">
                <span class='yasr_pro_stars_set'>
                    <?php
                        echo '<img src="' . esc_url(YASR_IMG_DIR . 'stars_rater.png').'">';
                    ?>
                </span>
            </label>
        </div>
        <div>
            <input type='radio' name='yasr_style_options[stars_set_free]' value='rater-yasr' id="radio-img-yasr"
                   class='yasr-general-options-scheme-color' <?php if ($style_options['stars_set_free'] === 'rater-yasr') {
                echo " checked=\"checked\" ";
            } ?> />
            <label for="radio-img-yasr">
                <span class='yasr_pro_stars_set'>
                    <?php
                        echo '<img src="' . esc_url(YASR_IMG_DIR . 'stars_rater_yasr.png').'">';
                    ?>
                </span>
            </label>
        </div>
        <div>
            <input type='radio' name='yasr_style_options[stars_set_free]' value='rater-oxy' id="radio-img-oxy"
                   class='yasr-general-options-scheme-color' <?php if ($style_options['stars_set_free'] === 'rater-oxy') {
                echo " checked=\"checked\" ";
            } ?> />
            <label for="radio-img-oxy">
                <span class='yasr_pro_stars_set'>
                    <?php
                    echo '<img src="' . esc_url(YASR_IMG_DIR . 'stars_rater_oxy.png').'">';
                    ?>
                </span>
            </label>
        </div>

    </div>

    <div id="yasr-settings-stylish-stars" style="margin-top: 30px">
        <div id="yasr-settings-stylish-image-container">
            <?php
                echo '<img id="yasr-settings-stylish-image" src=' . esc_url(YASR_IMG_DIR . 'yasr-pro-stars.png').'>';
            ?>
        </div>
    </div>

    <div id='yasr-settings-stylish-text'>
        <?php
            $text = __('Looking for more?', 'yet-another-stars-rating');
            $text .= '<br />';
            $text .= sprintf(__('Upgrade to %s', 'yet-another-stars-rating'), '<a href="?page=yasr_settings_page-pricing">Yasr Pro!</a>');

            echo wp_kses_post($text);
        ?>

    </div>

    <script type="text/javascript">
        jQuery('#yasr-settings-stylish-stars').mouseover(function () {
            jQuery('#yasr-settings-stylish-text').css("visibility", "visible");
            jQuery('#yasr-settings-stylish-image').css("opacity", 0.4);
        });
    </script>

    <?php
    submit_button(__('Save Settings', 'yet-another-stars-rating'));
}