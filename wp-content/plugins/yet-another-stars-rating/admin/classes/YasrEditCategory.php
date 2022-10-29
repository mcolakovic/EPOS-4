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


class YasrEditCategory {
    public static function init() {
        if (yasr_fs()->is_free_plan() && !yasr_fs()->is_trial()) {
            add_action( 'category_edit_form_fields', array('YasrEditCategory', 'categoryEditFormFieldsLock' ) );
        }
    }

    /**
     *
     */
    public static function categoryEditFormFieldsLock() {
        ?>
        <tr class="form-field term-name-wrap">
        <th scope="row">
            <label for="yasr-default-itemtype-category">
                <?php esc_html_e( 'Select default itemType', 'yet-another-stars-rating' ) ?>
            </label>
            <span class="dashicons dashicons-lock"></span>
            <?php

            ?>
            <span class="description">
                <?php
                $url = 'https://yetanotherstarsrating.com/?utm_source=wp-plugin&utm_medium=edit_category&utm_campaign=yasr_editor_category#yasr-pro';
                $url = esc_url($url);
                printf(
                    esc_html__('Upgrade to %s to unlock this feature', 'yet-another-stars-rating'),
                    sprintf(
                        '<a href="%s">%s</a>',
                        $url,
                        'YASR_PRO'
                    )
                ); ?>
            </span>
        </th>
        <td>
            <?php yasr_select_itemtype('yasr-pro-select-itemtype-category', 1, true ); ?>
            <p></p>
            <label for="yasr-pro-checkbox-itemtype-category" class="yasr-indented-answer">
                <input type="checkbox"
                       id="yasr-pro-checkbox-itemtype-category"
                       disabled
                >
                <span class="description">
                    <?php esc_html_e('Check to update YASR itemType', 'yet-another-stars-rating') ?>
                </span>
            </label>
        </td>
    </tr >
    <?php
    }
}
