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
 * @since 2.4.7
 *
 * Setting screen
 *
 * Class YasrSettings
 */
class YasrSettings {
    /**
     * Init Settings
     */
    public function init () {
        add_action('admin_init', array($this, 'generalOptions')); //This is for general options

        //include multiset functions
        require(YASR_ABSOLUTE_PATH_ADMIN . '/settings/multiset/yasr-settings-functions-multiset.php');

        //include style functions
        require(YASR_ABSOLUTE_PATH_ADMIN . '/settings/aspect_style/yasr-settings-style-functions.php');

        //load functions migration
        require(YASR_ABSOLUTE_PATH_ADMIN . '/settings/migrations/yasr-settings-migration-functions.php');
    }

    /**
     * Load general options
     */
    public function generalOptions() {
        register_setting(
            'yasr_general_options_group', // A settings group name. Must exist prior to the register_setting call.
            // This must match the group name in settings_fields()
            'yasr_general_options', //The name of an option to sanitize and save.
            array($this, 'sanitize')
        );

        $option = get_option('yasr_general_options');
        $option['auto_insert_enabled'] = (int)$option['auto_insert_enabled'];

        //This is to avoid undefined offset
        if ($option && $option['auto_insert_enabled'] === 0) {
            $option['auto_insert_what']             = 'visitor_rating';
            $option['auto_insert_where']            = 'bottom';
            $option['auto_insert_align']            = 'center';
            $option['auto_insert_exclude_pages']    = 'yes';
            $option['auto_insert_size']             = 'large';
            $option['auto_insert_custom_post_only'] = 'no';
        }

        if ($option && $option['stars_title'] === 'no') {
            $option['stars_title_what']           = 'visitor_rating';
            $option['stars_title_exclude_pages']  = 'yes';
            $option['stars_title_where']          = 'archive';
        }

        //Default values
        if(!isset($option['text_before_overall'])) {
            $option['text_before_overall']        = __('Our Score', 'yet-another-stars-rating');
        }
        if(!isset($option['text_before_visitor_rating'])) {
            $option['text_before_visitor_rating'] = __('Click to rate this post!', 'yet-another-stars-rating');
        }

        if(!isset($option['text_after_visitor_rating'])) {
            $option['text_after_visitor_rating']  = sprintf(
                __('[Total: %s  Average: %s]', 'yet-another-stars-rating'),
                '%total_count%', '%average%'
            );
        }

        if(!isset($option['custom_text_rating_saved'])) {
            $option['custom_text_rating_saved'] = __('Rating saved!', 'yet-another-stars-rating');
        }

        if(!isset($option['custom_text_rating_updated'])) {
            $option['custom_text_rating_updated'] = __('Rating updated!', 'yet-another-stars-rating');
        }

        if(!isset($option['custom_text_user_voted'])) {
            $option['custom_text_user_voted']     =
                __('You have already voted for this article with rating ', 'yet-another-stars-rating') . '%rating%';
        }

        if(!isset($option['custom_text_must_sign_in'])) {
            $option['custom_text_must_sign_in'] = __('You must sign in to vote', 'yet-another-stars-rating');
        }

        //Avoid undefined
        if (!isset($option['publisher'])) {
            $option['publisher'] = 'Organization';
        }

        if (!isset($option['publisher_name'])) {
            $option['publisher_name'] = get_bloginfo('name');
        }

        if (!isset($option['publisher_logo'])) {
            $option['publisher_logo'] = get_site_icon_url();
        }

        if(!isset($option['enable_ajax'])) {
            $option['enable_ajax'] = 'no';
        }

        add_settings_section(
            'yasr_general_options_section_id',
            __('General settings', 'yet-another-stars-rating'),
            array($this, 'sectionCallback'),
            'yasr_general_settings_tab'
        );

        add_settings_field(
            'yasr_use_auto_insert_id',
            yasr_description_auto_insert(),
            array($this, 'autoInsert'),
            'yasr_general_settings_tab',
            'yasr_general_options_section_id',
            $option
        );

        add_settings_field(
            'yasr_stars_title',
            yasr_description_stars_title(),
            array($this, 'starsTitle'),
            'yasr_general_settings_tab',
            'yasr_general_options_section_id',
            $option
        );

        add_settings_field(
            'yasr_show_overall_in_loop',
            yasr_description_archive_page(),
            array($this, 'archivePages'),
            'yasr_general_settings_tab',
            'yasr_general_options_section_id',
            $option
        );

        add_settings_field(
            'yasr_visitors_stats',
            yasr_description_vv_stats(),
            array($this, 'vvStats'),
            'yasr_general_settings_tab',
            'yasr_general_options_section_id',
            $option
        );

        add_settings_field(
            'yasr_allow_only_logged_in_id',
            yasr_description_allow_vote(),
            array($this, 'loggedOnly'),
            'yasr_general_settings_tab',
            'yasr_general_options_section_id',
            $option
        );

        add_settings_field(
            'yasr_custom_text',
            yasr_description_cstm_txt(),
            array($this, 'customText'),
            'yasr_general_settings_tab',
            'yasr_general_options_section_id',
            $option
        );


        add_settings_field(
            'yasr_choose_snippet_id',
            yasr_description_strucutured_data(),
            array($this, 'snippets' ),
            'yasr_general_settings_tab',
            'yasr_general_options_section_id',
            $option
        );

        add_settings_field(
            'yasr_advanced',
            __('Advanced Settings', 'yet-another-stars-rating'),
            array($this, 'advancedSettings'),
            'yasr_general_settings_tab',
            'yasr_general_options_section_id',
            $option
        );

    }

    /**
     * @return void
     */
    public function sectionCallback() {
    }

    /**
     * Display options for Auto insert
     *
     * @param $option
     */
    public function autoInsert($option) {
        ?>
        <div>
            <strong>
                <?php esc_html_e('Use Auto Insert?', 'yet-another-stars-rating'); ?>
            </strong>
            <div class="yasr-onoffswitch-big">
                <input type="checkbox" name="yasr_general_options[auto_insert_enabled]" class="yasr-onoffswitch-checkbox"
                       value="1" id="yasr_auto_insert_switch" <?php if ($option['auto_insert_enabled'] === 1) {
                    echo " checked='checked' ";
                } ?> >
                <label class="yasr-onoffswitch-label" for="yasr_auto_insert_switch">
                    <span class="yasr-onoffswitch-inner"></span>
                    <span class="yasr-onoffswitch-switch"></span>
                </label>
            </div>

            <div class="yasr-settings-row-35">
                <div>
                    <?php
                    $option_title = __('What?', 'yet-another-stars-rating');
                    $array_options = array (
                        'visitor_rating'  => __('Visitor Votes', 'yet-another-stars-rating'),
                        'overall_rating'  => __('Overall Rating', 'yet-another-stars-rating'),
                        'both'            => __('Both', 'yet-another-stars-rating')
                    );
                    $default = $option['auto_insert_what'];
                    $name    = 'yasr_general_options[auto_insert_what]';
                    $class   = 'yasr-auto-insert-options-class';

                    echo YasrPhpFieldsHelper::radio( $option_title, $class, $array_options, $name, $default );
                    ?>
                </div>
                <div>
                    <?php
                    $option_title = __('Where?', 'yet-another-stars-rating');
                    $array_options = array (
                        'top'     => __('Before the content', 'yet-another-stars-rating'),
                        'bottom'  => __('After the content', 'yet-another-stars-rating'),
                    );
                    $default = $option['auto_insert_where'];
                    $name    = 'yasr_general_options[auto_insert_where]';
                    $class   = 'yasr-auto-insert-options-class';

                    echo YasrPhpFieldsHelper::radio( $option_title, $class, $array_options, $name, $default );
                    ?>
                </div>
                <div>
                    <?php
                    $option_title = __('Align', 'yet-another-stars-rating');
                    $array_options = array (
                        'left'     => __('Left', 'yet-another-stars-rating'),
                        'center'   => __('Center', 'yet-another-stars-rating'),
                        'right'    => __('Right', 'yet-another-stars-rating')
                    );
                    $default = $option['auto_insert_align'];
                    $name    = 'yasr_general_options[auto_insert_align]';
                    $class   = 'yasr-auto-insert-options-class';

                    echo YasrPhpFieldsHelper::radio($option_title, $class, $array_options, $name, $default);
                    ?>
                </div>
                <div>
                    <strong>
                        <?php esc_html_e('Size', 'yet-another-stars-rating'); ?>
                    </strong>
                    <?php
                        $name  = 'yasr_general_options[auto_insert_size]';
                        $class = 'yasr-auto-insert-options-class';
                        $id    = 'yasr-auto-insert-options-stars-size-';

                        YasrPhpFieldsHelper::radioSelectSize($name, $class, $option['auto_insert_size'], $id);
                    ?>
                </div>
                <div>
                    <?php
                        $option_title = __('Exclude Pages?', 'yet-another-stars-rating');
                        $array_options = array (
                            'yes'  => __('Yes', 'yet-another-stars-rating'),
                            'no'   => __('No', 'yet-another-stars-rating'),
                        );
                        $default = $option['auto_insert_exclude_pages'];
                        $name    = 'yasr_general_options[auto_insert_exclude_pages]';
                        $class   = 'yasr-auto-insert-options-class';

                        echo YasrPhpFieldsHelper::radio( $option_title, $class, $array_options, $name, $default );
                    ?>
                </div>
                <?php
                    $custom_post_types = YasrCustomPostTypes::getCustomPostTypes();
                    if ($custom_post_types) {
                        echo '<div>';
                            $option_title = __('Use only in custom post types?', 'yet-another-stars-rating');
                            $array_options = array (
                                'yes'  => __('Yes', 'yet-another-stars-rating'),
                                'no'   => __('No', 'yet-another-stars-rating'),
                            );
                            $default = $option['auto_insert_custom_post_only'];
                            $name    = 'yasr_general_options[auto_insert_custom_post_only]';
                            $class   = 'yasr-auto-insert-options-class';

                            echo YasrPhpFieldsHelper::radio( $option_title, $class, $array_options, $name, $default );
                            ?>
                            <div>
                                <?php
                                    esc_html_e('Select yes if you want to use auto insert only in custom post types',
                                    'yet-another-stars-rating');
                                ?>
                            </div>
                            <?php
                        echo '</div>';
                    } else {
                        ?>
                            <input type="hidden" name="yasr_general_options[auto_insert_custom_post_only]" value="no">
                        <?php
                    }
                ?>
            </div>
            <?php submit_button(YASR_SAVE_All_SETTINGS_TEXT); ?>
        </div>
        <hr />
        <?php

    } //End yasr_auto_insert_callback

    /**
     * Display options for stars near title
     *
     * @param $option
     */
    public function starsTitle($option) {
        ?>
        <div>
            <div class="yasr-onoffswitch-big">
                <input type="checkbox" name="yasr_general_options[stars_title]" class="yasr-onoffswitch-checkbox"
                       id="yasr-general-options-stars-title-switch" <?php if ($option['stars_title'] === 'yes') {
                    echo " checked='checked' ";
                } ?> >
                <label class="yasr-onoffswitch-label" for="yasr-general-options-stars-title-switch">
                    <span class="yasr-onoffswitch-inner"></span>
                    <span class="yasr-onoffswitch-switch"></span>
                </label>
            </div>
            <div class="yasr-settings-row-35">
                <div>
                    <?php
                        $option_title = __('What?', 'yet-another-stars-rating');
                        $array_options = array (
                            'visitor_rating'  => __('Visitor Votes', 'yet-another-stars-rating'),
                            'overall_rating'  => __('Overall Rating / Author Rating', 'yet-another-stars-rating'),
                        );
                        $default = $option['stars_title_what'];
                        $name    = 'yasr_general_options[stars_title_what]';
                        $class   = 'yasr-stars-title-options-class';

                        echo YasrPhpFieldsHelper::radio( $option_title, $class, $array_options, $name, $default );
                    ?>
                </div>
                <div>
                    <?php
                    $option_title = __('Exclude Pages?', 'yet-another-stars-rating');
                    $array_options = array (
                        'yes'  => __('Yes', 'yet-another-stars-rating'),
                        'no'   => __('No', 'yet-another-stars-rating'),
                    );
                    $default = $option['stars_title_exclude_pages'];
                    $name    = 'yasr_general_options[stars_title_exclude_pages]';
                    $class   = 'yasr-stars-title-options-class';

                    echo YasrPhpFieldsHelper::radio( $option_title, $class, $array_options, $name, $default );
                    ?>
                </div>
                <div style="flex: 0 0 50%">
                    <?php
                    $option_title = __('Where do you want show ratings?', 'yet-another-stars-rating');
                    $array_options = array (
                        'archive'  => __('Only on archive pages (categories, tags, etc.)', 'yet-another-stars-rating'),
                        'single'   => __('Only on single posts or pages', 'yet-another-stars-rating'),
                        'both'     => __('Both', 'yet-another-stars-rating'),
                    );
                    $default = $option['stars_title_where'];
                    $name    = 'yasr_general_options[stars_title_where]';
                    $class   = 'yasr-stars-title-options-class';

                    echo YasrPhpFieldsHelper::radio( $option_title, $class, $array_options, $name, $default );
                    ?>
                </div>
            </div>
        </div>

        <p>&nbsp;</p>
        <hr />

        <?php

    }

    public function archivePages($option) {
        ?>
        <div class="yasr-settings-row-45">
            <div>
                <strong>
                    <?php esc_html_e('Show "Overall Rating" in Archive Pages?', 'yet-another-stars-rating'); ?>
                </strong>
                <div class="yasr-onoffswitch-big">
                    <input type="checkbox" name="yasr_general_options[show_overall_in_loop]" class="yasr-onoffswitch-checkbox"
                           id="yasr-show-overall-in-loop-switch" <?php if($option['show_overall_in_loop'] === 'enabled') {
                        echo " checked='checked' ";
                    } ?> >
                    <label class="yasr-onoffswitch-label" for="yasr-show-overall-in-loop-switch">
                        <span class="yasr-onoffswitch-inner"></span>
                        <span class="yasr-onoffswitch-switch"></span>
                    </label>
                </div>
                <br/>
                <?php esc_html_e('Enable to show "Overall Rating" in archive pages.','yet-another-stars-rating') ?>
            </div>

            <div>
                <strong>
                    <?php esc_html_e('Show "Visitor Votes" in Archive Page?', 'yet-another-stars-rating') ?>
                </strong>
                <div class="yasr-onoffswitch-big">
                    <input type="checkbox" name="yasr_general_options[show_visitor_votes_in_loop]" class="yasr-onoffswitch-checkbox"
                           id="yasr-show-visitor-votes-in-loop-switch" <?php if ($option['show_visitor_votes_in_loop'] === 'enabled') {
                        echo " checked='checked' ";
                    } ?> >
                    <label class="yasr-onoffswitch-label" for="yasr-show-visitor-votes-in-loop-switch">
                        <span class="yasr-onoffswitch-inner"></span>
                        <span class="yasr-onoffswitch-switch"></span>
                    </label>
                </div>
                <br/>
                <?php esc_html_e('Enable to show "Visitor Votes" in archive pages','yet-another-stars-rating') ?>
            </div>

        </div>
        <p>&nbsp;</p>
        <hr>
        <?php

    }

    public function vvStats($option) {
        ?>
        <div class="yasr-settings-row">
            <div class="yasr-settings-col-20">
                <div class="yasr-onoffswitch-big">
                    <input type="checkbox" name="yasr_general_options[visitors_stats]" class="yasr-onoffswitch-checkbox"
                           id="yasr-general-options-visitors-stats-switch" <?php if ($option['visitors_stats'] === 'yes') {
                        echo " checked='checked' ";
                    } ?> >
                    <label class="yasr-onoffswitch-label" for="yasr-general-options-visitors-stats-switch">
                        <span class="yasr-onoffswitch-inner"></span>
                        <span class="yasr-onoffswitch-switch"></span>
                    </label>
                </div>
                <br/>
                <?php
                    esc_html_e('Select "Yes" to enable.', 'yet-another-stars-rating');
                ?>
                <br />
                <p>&nbsp;</p>
            </div>
            <div class="yasr-settings-col-70">
                <strong>
                    <?php esc_html_e('Example', 'yet-another-stars-rating') ?>:
                </strong>
                <br />
                <img src="<?php echo esc_url(YASR_IMG_DIR . 'yasr-settings-stats.png')?>"
                     class="yasr-help-box-settings"
                     style="display: block; width: 330px"
                     alt="yasr-statsexplained">
            </div>
        </div>
        <hr />
        <?php

    }

    public function loggedOnly($option) {
        ?>
        <div class="yasr-settings-padding-left">
            <?php
            $array_options = array(
                'logged_only' => __('Allow only logged-in users', 'yet-another-stars-rating' ),
                'allow_anonymous'  => __('Allow everybody (logged in and anonymous)', 'yet-another-stars-rating' ),
            );
            $default       = $option['allowed_user'];
            $name          = 'yasr_general_options[allowed_user]';
            $class         = 'yasr_auto_insert_loggedonly';

            echo YasrPhpFieldsHelper::radio( false, $class, $array_options, $name, $default );
            ?>
            <br />
            <div class="yasr-indented-answer">
                <?php
                    esc_html_e('Select who can rate your posts.','yet-another-stars-rating')
                ?>
            </div>
        </div>
        <p>&nbsp;</p>
        <hr />
        <?php

    } //End function

    public function customText($option) {
        ?>
        <div>
            <?php
                $custom_text = array(
                    'txt_before_overall' => array (
                        'name'        => 'text_before_overall',
                        'description' => __('Custom text to display before Overall Rating', 'yet-another-stars-rating'),
                        'id'          => 'yasr-settings-custom-text-before-overall',
                        'class'       => 'yasr-general-options-text-before'
                    ),
                    'txt_before_vv'       => array (
                        'name'        => 'text_before_visitor_rating',
                        'description' => __('Custom text to display BEFORE Visitor Rating', 'yet-another-stars-rating'),
                        'id'          => 'yasr-settings-custom-text-before-visitor',
                        'class'       => 'yasr-general-options-text-before'
                    ),
                    'txt_after_vv'        => array (
                        'name'        => 'text_after_visitor_rating',
                        'description' => __('Custom text to display AFTER Visitor Rating', 'yet-another-stars-rating'),
                        'id'          => 'yasr-settings-custom-text-after-visitor',
                        'class'       => 'yasr-general-options-text-before'
                    ),
                    'txt_login_required'  => array (
                        'name'        => 'custom_text_must_sign_in',
                        'description' => __('Custom text to display when login is required to vote', 'yet-another-stars-rating'),
                        'id'          => 'yasr-settings-custom-text-must-sign-in',
                        'class'       => 'yasr-general-options-text-before'
                    ),
                    'txt_vv_rating_saved' => array (
                        'name'        => 'custom_text_rating_saved',
                        'description' => __('Custom text to display when rating is saved', 'yet-another-stars-rating'),
                        'id'          => 'yasr-settings-custom-text-rating-saved',
                        'class'       => 'yasr-general-options-text-before'
                    ),
                    'txt_vv_rating_updated' => array (
                        'name'        => 'custom_text_rating_updated',
                        'description' => __('Custom text to display when rating is updated (only for logged in users)',
                            'yet-another-stars-rating'),
                        'id'          => 'yasr-settings-custom-text-rating-updated',
                        'class'       => 'yasr-general-options-text-before'
                    ),
                    'txt_vv_rated'        => array (
                        'name'        => 'custom_text_user_voted',
                        'description' => __('Custom text to display when an user has already rated', 'yet-another-stars-rating'),
                        'id'          => 'yasr-settings-custom-text-already-rated',
                        'class'       => 'yasr-general-options-text-before'
                    ),
                );
            ?>
            <div class="yasr-settings-row-45">
                <div id="yasr-general-options-custom-text">
                    <?php
                        self::echoSettingFields($custom_text, $option);
                    ?>
                    <input type="button"
                       id="yasr-settings-custom-texts"
                       class="button"
                       value="<?php esc_attr_e('Restore defaults', 'yet-another-stars-rating') ?>">
                </div>

                <div id="yasr-doc-custom-text-div">
                    <div class="yasr-help-box-settings" style="display: block">
                        <?php
                            $string_custom_overall = sprintf(
                                    __('In the first and last fields you can use %s pattern to show the rating (as text).',
                                'yet-another-stars-rating'), '<strong>%rating%</strong>');

                            $string_custom_visitor = sprintf(__('In the second and third fields you can use %s pattern to show the 
                            total count, and %s pattern to show the average.', 'yet-another-stars-rating'),
                                '<strong>%total_count%</strong>', '<strong>%average%</strong>');

                            esc_html_e('Leave a field empty to disable it.', 'yet-another-stars-rating');
                            echo '<br /><br/>';
                            echo wp_kses_post($string_custom_overall);
                            echo '<br /><br/>';
                            echo wp_kses_post($string_custom_visitor);
                            echo '<br /><br/>';

                            esc_html_e('You can use these html tags:', 'yet-another-stars-rating');
                            echo ' <strong>' . esc_html('<strong>, <p>') . '.</strong>';
                        ?>
                    </div>
                </div>
            </div>
            <br />
            <?php
                submit_button(YASR_SAVE_All_SETTINGS_TEXT);
            ?>
        </div>
        <hr />
        <?php
    }

    public function snippets($option) {
        $publisher_name = $option['publisher_name'];
        $publisher_logo = $option['publisher_logo'];
        ?>
        <div class="yasr-settings-padding-left yasr-settings-row">
            <div class="yasr-settings-col-60">
                <strong>
                    <?php esc_html_e('Select default itemType for all post or pages', 'yet-another-stars-rating'); ?>
                </strong>
                <div>
                    <?php
                        $review_types  = json_decode(YASR_SUPPORTED_SCHEMA_TYPES);
                        sort($review_types);
                    ?>
                    <label for="yasr-choose-reviews-types-list">
                        <select name="yasr_general_options[snippet_itemtype]" id="yasr-choose-reviews-types-list">
                            <?php
                                foreach ($review_types as $type) {
                                    $type = trim($type);
                                    $type_option = $type;
                                    //to keep compatibility with version <2.2.3
                                    if($type === 'Place') {
                                        $type_option='LocalBusiness';
                                    }
                                    //to keep compatibility with version <2.2.3
                                    if($type === 'Other') {
                                        $type_option='BlogPosting';
                                    }

                                    if ($option['snippet_itemtype'] === $type) {
                                        echo "<option value=\"$type\" selected>$type_option</option>";
                                    } else {
                                        echo "<option value=\"$type\">$type_option</option>";
                                    }
                                }
                            ?>
                        </select>
                    </label>
                    <div class="yasr-element-row-container-description">
                        <?php
                            esc_html_e('You can always change itemType in the single post or page.',
                            'yet-another-stars-rating');
                        ?>
                    </div>

                    <?php
                        $option_title = __('Choose whether the site represents an organization or a person.', 'yet-another-stars-rating');
                        $array_options = array (
                            'Organization'  => 'Organization',
                            'Person'        => 'Person'
                        );
                        $default = $option['publisher'];
                        $name    = 'yasr_general_options[publisher]';
                        $id      = 'yasr-general-options-publisher';

                        echo YasrPhpFieldsHelper::radio( $option_title, 'none', $array_options, $name, $default, $id );
                    ?>
                    <br/>
                    <input type='text' name='yasr_general_options[publisher_name]'
                           id="yasr-general-options-publisher-name"
                           class="yasr-additional-info-inputs" <?php printf('value="%s"', esc_attr($publisher_name)); ?>
                           maxlength="180"/>
                    <div class="yasr-element-row-container-description">
                        <label for="yasr-general-options-publisher-name">
                            <?php esc_html_e('Publisher name (e.g. Google)', 'yet-another-stars-rating') ?>
                        </label>
                    </div>

                    <input type='text' name='yasr_general_options[publisher_logo]'
                           id="yasr-general-options-publisher-logo"
                           class="yasr-blogPosting-additional-info-inputs"
                        <?php printf('value="%s"', esc_url($publisher_logo)); ?>
                           maxlength="300"/>
                    <div class="yasr-element-row-container-description">
                        <label for="yasr-general-options-publisher-logo">
                            <?php esc_html_e('Image Url (if empty siteicon will be used instead)', 'yet-another-stars-rating') ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="yasr-settings-col-40" id="yasr-blogPosting-additional-info">
                <div class="yasr-help-box-settings" style="display:block">
                    <?php
                        echo wp_kses_post(sprintf(
                            __('Please keep in mind that since September, 16, 2019 blogPosting itemType will 
                                        no show stars in SERP anymore. %sHere%s the announcement by Google.',
                                'yet-another-stars-rating'),
                            '<br /><br /><a href="https://webmasters.googleblog.com/2019/09/making-review-rich-results-more-helpful.html">',
                            '</a>')
                        );
                        echo "<br /><br />";
                        echo wp_kses_post(sprintf(
                            __('Also, %sread Google guidelines%s', 'yet-another-stars-rating'),
                            '<a href="https://developers.google.com/search/docs/data-types/review-snippet#guidelines">',
                            '</a>.')
                        );
                    ?>
                </div>
            </div>
        </div>
        <?php
            submit_button(YASR_SAVE_All_SETTINGS_TEXT);
        ?>

        <hr />
        <?php

    } //End function yasr_choose_snippet_callback

    public function advancedSettings($option) {
        ?>
        <div class="yasr-settings-row-45">
            <div>
                <strong>
                    <?php
                        esc_html_e('Load results with AJAX?', 'yet-another-stars-rating');
                    ?>
                </strong>
                <div class="yasr-onoffswitch-big">
                    <input type="checkbox" name="yasr_general_options[enable_ajax]" class="yasr-onoffswitch-checkbox"
                           id="yasr-general-options-enable-ajax-switch" <?php if ($option['enable_ajax'] === 'yes') {
                        echo " checked='checked' ";
                    } ?> >
                    <label class="yasr-onoffswitch-label" for="yasr-general-options-enable-ajax-switch">
                        <span class="yasr-onoffswitch-inner"></span>
                        <span class="yasr-onoffswitch-switch"></span>
                    </label>
                </div>
                <br/>
                <?php
                    esc_html_e('This should be enabled if you\'re using caching plugins. 
                        Not required for yasr_overall_rating and yasr_multiset.',
                        'yet-another-stars-rating'
                    );
                    $caching_plugin = new YasrFindCachingPlugins();
                    $caching_plugin_found = $caching_plugin->cachingPluginFound();
                    if($caching_plugin_found !== false) {
                        echo wp_kses_post('<div class="yasr-element-row-container-description">'.
                            sprintf(
                                __('Since you\'re using the caching plugin %s you should enable this.',
                                    'yet-another-stars-rating'),
                                '<strong>'.$caching_plugin_found.'</strong>'
                            ).
                            '</div>');
                    }
                ?>
            </div>
            <div>
                <strong>
                    <?php esc_html_e('Do you want to save ip address?', 'yet-another-stars-rating') ?>
                </strong>
                <div class="yasr-onoffswitch-big">
                    <input type="checkbox" name="yasr_general_options[enable_ip]" class="yasr-onoffswitch-checkbox"
                           id="yasr-general-options-enable-ip-switch" <?php if ($option['enable_ip'] === 'yes') {
                        echo " checked='checked' ";
                    } ?> >
                    <label class="yasr-onoffswitch-label" for="yasr-general-options-enable-ip-switch">
                        <span class="yasr-onoffswitch-inner"></span>
                        <span class="yasr-onoffswitch-switch"></span>
                    </label>
                </div>
                <br/>
                <?php
                    $string = sprintf(
                        __('Please note that to comply with the %s EU law, you %s MUST %s warn your users that you\'re storing their ip. %s
                                        If in doubt, leave no.',
                            'yet-another-stars-rating'
                        ),
                        '<a href="https://en.wikipedia.org/wiki/General_Data_Protection_Regulation">GDPR</a>',
                        '<strong>', '</strong>', '<br />'
                    );
                    echo wp_kses_post($string);
                    ?>
            </div>
        </div>
        <?php
    } //End function

    public function sanitize($option) {
        //Array to return
        $output = array();

        $tidy_installed = false;

        if (extension_loaded('tidy')) {
            $tidy_release_date = strtotime(tidy_get_release());
            $tidy_working_release_date = strtotime('2017/11/25');

            if ($tidy_release_date >= $tidy_working_release_date) {
                $tidy_installed = true;
            }
        }

        // Loop through each of the incoming options
        foreach ($option as $key => $value) {
            // Check to see if the current option has a value. If so, process it.
            if (isset($option[$key])) {

                //Tags are not allowed for any fields
                $allowed_tags = '';

                //except these ones
                if ($key === 'text_before_overall' || $key === 'text_before_visitor_rating' ||
                    $key === 'text_after_visitor_rating' || $key === 'custom_text_must_sign_in'  ||
                    $key === 'custom_text_rating_saved'  || $key === 'custom_text_rating_updated' ||
                    $key === 'custom_text_user_voted') {

                    $allowed_tags = '<strong><p>';

                    // handle quoted strings and allow some tags
                    $output[$key] = strip_tags(stripslashes($option[$key]), $allowed_tags);

                    //if tidy extension is enabled, fix errors in html
                    if ($tidy_installed === true) {
                        $tidy         = new Tidy();
                        $output[$key] = $tidy->repairString($output[$key], array('show-body-only' => true));
                    }

                }
                else {
                    // handle quoted strings and allow no tags
                    $output[$key] = strip_tags(stripslashes($option[$key]), $allowed_tags);
                }

                if ($key === 'publisher_logo') {
                    //if is not a valid url get_site_icon_url instead
                    if (filter_var($value, FILTER_VALIDATE_URL) === false) {
                        $output[$key] = get_site_icon_url();
                    }
                }

            } // end if

        } // end foreach

        /** The following steps are needed to avoid undefined index if a setting is saved to "no"  **/

        //if in array doesn't exists [auto_insert_enabled] key, create it and set to 0
        if (!array_key_exists('auto_insert_enabled', $output)) {
            $output['auto_insert_enabled'] = 0;
        }
        //if exists value must be 1
        else {
            $output['auto_insert_enabled'] = 1;
        }

        //if in array doesn't exists [stars title] key, create it and set to 'no'
        if (!array_key_exists('stars_title', $output)) {
            $output['stars_title'] = 'no';
        }
        //if exists value must be 1
        else {
            $output['stars_title'] = 'yes';
        }

        //Same as above but for [show_overall_in_loop] key
        if (!array_key_exists('show_overall_in_loop', $output)) {
            $output['show_overall_in_loop'] = 'disabled';
        }
        //if exists must be string 'enabled'
        else {
            $output['show_overall_in_loop'] = 'enabled';
        }

        //Same as above but for [show_visitor_votes_in_loop] key
        if (!array_key_exists('show_visitor_votes_in_loop', $output)) {
            $output['show_visitor_votes_in_loop'] = 'disabled';
        }
        //if exists must be string 'enabled'
        else {
            $output['show_visitor_votes_in_loop'] = 'enabled';
        }

        //Same as above but for visitors_stats key
        if (!array_key_exists('visitors_stats', $output)) {
            $output['visitors_stats'] = 'no';
        }
        //if exists must be string 'yes'
        else {
            $output['visitors_stats'] = 'yes';
        }

        //Same as above but for enable_ip key
        if (!array_key_exists('enable_ip', $output)) {
            $output['enable_ip'] = 'no';
        }
        //if exists must be string 'yes'
        else {
            $output['enable_ip'] = 'yes';
        }

        //Same as above but for enable_ip key
        if (!array_key_exists('enable_ajax', $output)) {
            $output['enable_ajax'] = 'no';
        }
        //if exists must be string 'yes'
        else {
            $output['enable_ajax'] = 'yes';
        }

        return $output;

    }

    public static function echoSettingFields($elementsType_array, $option) {
        $string_input = false;

        foreach($elementsType_array as $property) {

            //concatenate yasr_general_options with property name
            $element_name = 'yasr_general_options[' . $property['name'] . ']';

            if(isset($property['type'])) {
                if($property['type'] === 'select') {
                    $string_input = YasrPhpFieldsHelper::select(
                        '', $property['label'], $property['options'], $property['name'], '', esc_attr($option[$property['name']])
                    );
                } elseif($property['type'] === 'textarea') {
                    $string_input = YasrPhpFieldsHelper::textArea('', '', $property['name'], '', '',
                        esc_textarea($option[$property['name']]));
                }
            } //to use text, there is no need to set the type element
            else {
                $string_input = YasrPhpFieldsHelper::text(
                    $property['class'], '', $element_name, $property['id'], '', esc_attr($option[$property['name']])
                );
            }

            echo htmlspecialchars_decode($string_input);

            if(isset($property['description']) && $property['description'] !== '') {
                echo '<div class="yasr-element-row-container-description">';
                echo esc_html($property['description']);
                echo '</div>';
            }

        }
    }


}