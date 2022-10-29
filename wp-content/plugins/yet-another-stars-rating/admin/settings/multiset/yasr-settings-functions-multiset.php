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

/**************** Add yasr multiset options and settings ************/

add_action('admin_init', 'yasr_multiset_options_init'); //This is for general options

function yasr_multiset_options_init() {
    register_setting(
        'yasr_multiset_options_group', // A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
        'yasr_multiset_options', //The name of an option to sanitize and save.
        'yasr_sanitize_multiset_options'
    );

    $option_multiset = get_option('yasr_multiset_options');

    if ($option_multiset === false) {
        $option_multiset = array(
                'show_average' => 'no'
        );
    }

    if (!isset($option_multiset['show_average'])) {
        $option_multiset['show_average'] = 'yes';
    }

    add_settings_section('yasr_multiset_options_section_id', '', 'yasr_multiset_section_callback', 'yasr_multiset_tab');
    add_settings_field('yasr_multiset_hide_average_id', __('Show average?', 'yet-another-stars-rating'), 'yasr_multiset_hide_average_callback', 'yasr_multiset_tab', 'yasr_multiset_options_section_id', $option_multiset);

}

function yasr_multiset_section_callback() {
    //Silence
}

function yasr_multiset_hide_average_callback($option_multiset) {

    ?>

    <div class="yasr-onoffswitch-big">
        <input type="checkbox" name="yasr_multiset_options[show_average]" class="yasr-onoffswitch-checkbox"
               id="yasr-multiset-options-show-average-switch" <?php if ($option_multiset['show_average'] === 'yes') {
            echo " checked='checked' ";
        } ?> >
        <label class="yasr-onoffswitch-label" for="yasr-multiset-options-show-average-switch">
            <span class="yasr-onoffswitch-inner"></span>
            <span class="yasr-onoffswitch-switch"></span>
        </label>
    </div>

    <br/>

    <br/>

    <?php

    esc_html_e('If you select no, the "Average" row will not be displayed. 
        You can override this in the single multi set by using the parameter "show_average"',
        'yet-another-stars-rating');

}


function yasr_sanitize_multiset_options($option_multiset) {

    if (is_array($option_multiset)) {
        if( ! array_key_exists('show_average', $option_multiset)) {
            $option_multiset['show_average'] = 'no';
        } else {
            $option_multiset['show_average'] = 'yes';
        }
    } else {
        $option_multiset['show_average'] = 'no';
    }

    return $option_multiset;

}


/****** Create a form for settings page to create new multi set ******/
function yasr_display_multi_set_form() {
    ?>

    <h4 class="yasr-multi-set-form-headers">
        <?php esc_html_e("Add New Multiple Set", 'yet-another-stars-rating'); ?>
    </h4>

    <p>
        <em>
        <?php esc_html_e('Name, Element#1 and Element#2 MUST be filled and must be long at least 3 characters',
            'yet-another-stars-rating') ?>
        </em>
    </p>

    <div>
        <form action="<?php echo admin_url('options-general.php?page=yasr_settings_page&tab=manage_multi') ?>"
              id="form_add_multi_set" method="post">

           <?php  wp_nonce_field('add-multi-set', 'add-nonce-new-multi-set') //Must be inside the form ?>

            <strong><?php esc_html_e("Name", 'yet-another-stars-rating') ?></strong>

            <input type="text" name="multi-set-name" id="new-multi-set-name" class="input-text-multi-set">
            <br />

            <?php esc_html_e("You can insert up to nine elements", 'yet-another-stars-rating') ?>
            <br/>

            <?php for ($i = 1; $i <= 9; $i ++) {
                echo "<strong>" . __('Element ', 'yet-another-stars-rating') . "#$i" . "</strong>";
                ?>
                <input type="text" name="multi-set-name-element-<?php echo $i ?>" id="multi-set-name-element-<?php echo $i ?>"
                       class="input-text-multi-set">
                <br/>

            <?php } //End foreach ?>

            <br/>
            <input type="submit" value="<?php esc_attr_e("Create New Set", 'yet-another-stars-rating') ?>" class="button-primary"/>
        </form>
    </div>

    <?php
} //End function


function yasr_edit_multi_form() {

    global $wpdb;

    $multi_set = YasrMultiSetData::returnMultiSetNames();

    $n_multi_set = (int)$wpdb->num_rows; //wpdb->num_rows always store the last of the last query

    if ($n_multi_set > 1) {
        ?>

        <div class="yasr-manage-multiset">
            <h4 class="yasr-multi-set-form-headers"><?php esc_html_e("Manage Multiple Set", 'yet-another-stars-rating'); ?></h4>

            <?php esc_html_e('Wich set do you want to edit or remove?', 'yet-another-stars-rating') ?>

            <select id="yasr_select_edit_set">
                <?php foreach ($multi_set as $name) { ?>
                    <option value="<?php echo $name->set_id ?>"><?php echo $name->set_name ?></option>
                <?php } //End foreach ?>
            </select>

            <button href="#" class="button-delete" id="yasr-button-select-set-edit-form"><?php esc_html_e("Select"); ?></button>
        </div>

        <?php

    } //End if n_multi_set >1

    elseif ($n_multi_set === 1) {

        $set_name = $wpdb->get_results("SELECT field_name AS name, field_id AS id, parent_set_id AS set_id
			                            FROM " . YASR_MULTI_SET_FIELDS_TABLE . "
			                            ORDER BY field_id");

        foreach ($multi_set as $find_set_id) {
            $set_type = $find_set_id->set_id;
        }

        ?>

        <div class="yasr-manage-multiset-single">

            <h4 class="yasr-multi-set-form-headers"><?php esc_html_e("Manage Multiple Set", 'yet-another-stars-rating'); ?></h4>

            <form action=" <?php echo admin_url('options-general.php?page=yasr_settings_page&tab=manage_multi') ?>"
                  id="form_edit_multi_set" method="post">

                <input type="hidden" name="yasr_edit_multi_set_form" value="<?php echo $set_type ?>"/>

                <table id="yasr-table-form-edit-multi-set">
                    <tr>
                        <td id="yasr-table-form-edit-multi-set-header">
                            <?php esc_html_e('Field name', 'yet-another-stars-rating') ?>
                        </td>

                        <td id="yasr-table-form-edit-multi-set-remove">
                            <?php esc_html_e('Remove', 'yet-another-stars-rating') ?>
                        </td>
                    </tr>

                    <?php

                    $i = 1;

                    foreach ($set_name as $name) {

                        echo "
			                <tr>
			                    <td width=\"80%\">
			                        Element #$i <input type=\"text\" value=\"$name->name\" name=\"edit-multi-set-element-$i\">
			                        <input type=\"hidden\" value=\"$name->id\" name=\"db-id-for-element-$i\">
			                    </td>

			                    <td width=\"20%\" style=\"text-align:center\">
			                        <input type=\"checkbox\" value=\"$name->id\" name=\"remove-element-$i\">
			                    </td>
		                	</tr>
		                	";

                        $i ++;

                    }

                    $i = $i - 1; //This is the number of the fields

                    echo "

			            <input type=\"hidden\" name=\"yasr-edit-form-number-elements\" id=\"yasr-edit-form-number-elements\" value=\"$i\">

			            </table>

			            <table width=\"100%\" class=\"yasr-edit-form-remove-entire-set\">
			            <tr>

			                <td width=\"80%\">" . __("Remove whole set?", 'yet-another-stars-rating') . "</td>

			                <td width=\"20%\" style=\"text-align:center\">
			                    <input type=\"checkbox\" name=\"yasr-remove-multi-set\" value=\"$set_type\">
			                </td>

			            </tr>

			            </table>

			            ";

                    echo "<p>";
                    esc_html_e("If you remove something you will remove all the votes for that set or field. This operation CAN'T BE undone.", 'yet-another-stars-rating');
                    echo "</p>";

                    wp_nonce_field('edit-multi-set', 'add-nonce-edit-multi-set')

                    ?>

                    <div id="yasr-element-limit"
                         style="display:none; color:red"><?php esc_html_e("You can use up to 9 elements", 'yet-another-stars-rating') ?></div>

                    <input type="button"
                           class="button-delete"
                           id="yasr-add-field-edit-multiset"
                           value="<?php esc_attr_e('Add element', 'yet-another-stars-rating'); ?>"
                    >

                    <input type="submit"
                           value="<?php esc_attr_e('Save changes', 'yet-another-stars-rating') ?>"
                           class="button-primary">

            </form>

        </div>

        <?php
    } else {
        esc_html_e("No Multiple Set were found", 'yet-another-stars-rating');
    }

}//End function


/****** Get and output multiple set in a form and table, used in settings page ******/

add_action('wp_ajax_yasr_get_multi_set', 'yasr_get_multi_set_callback');
function yasr_get_multi_set_callback() {
    $set_id = (int)$_POST['set_id'];

    global $wpdb;

    $set_name = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT field_name AS name, field_id AS id
                    FROM " . YASR_MULTI_SET_FIELDS_TABLE . "
                    WHERE parent_set_id= %d
                    ORDER BY field_id",
            $set_id)
    );

    if($set_name === null) {
        return;
    }

    ?>

    <form action="<?php echo admin_url('options-general.php?page=yasr_settings_page&tab=manage_multi') ?>"
          id="form_edit_multi_set" method="post">
        <input type="hidden" name="yasr_edit_multi_set_form" value="<?php echo $set_id ?>"/>

        <table id="yasr-table-form-edit-multi-set">
            <tr>
                <td id="yasr-table-form-edit-multi-set-header">
                    <?php esc_html_e('Field name', 'yet-another-stars-rating') ?>
                </td>

                <td id="yasr-table-form-edit-multi-set-remove">
                    <?php esc_html_e('Remove', 'yet-another-stars-rating') ?>
                </td>
            </tr>

            <?php

            $i = 1;

            foreach ($set_name as $name) {
                echo "
                <tr>
                    <td width='80%'>
                        Element #$i <input type='text' value='$name->name' name='edit-multi-set-element-$i'>
                        <input type='hidden' value='$name->id' name='db-id-for-element-$i'>
                    </td>

                    <td width='20%' style=\"text-align:center\">
                        <input type=\"checkbox\" value=\"$name->id\" name=\"remove-element-$i\">
                    </td>
                </tr>
                ";
                $i ++;
            }

            $i = $i - 1; //This is the number of the fields

            echo "

            <input type=\"hidden\" name=\"yasr-edit-form-number-elements\" id=\"yasr-edit-form-number-elements\" value=\"$i\">

            </table>

            <table width=\"100%\" class=\"yasr-edit-form-remove-entire-set\">
            <tr>

                <td width=\"80%\">" . __("Remove whole set?", 'yet-another-stars-rating') . "</td>

                <td width=\"20%\" style=\"text-align:center\">
                    <input type=\"checkbox\" name=\"yasr-remove-multi-set\" value=\"$set_id\">
                </td>

            </tr>

            </table>

            ";

            echo "<p>";
            esc_html_e("If you remove something you will remove all the votes for that set or field. This operation CAN'T BE undone.", 'yet-another-stars-rating');
            echo "</p>";

            wp_nonce_field('edit-multi-set', 'add-nonce-edit-multi-set')

            ?>

            <div id="yasr-element-limit"
                 style="display:none; color:red"><?php esc_html_e("You can use up to 9 elements", 'yet-another-stars-rating') ?></div>

            <input type="button" class="button-delete" id="yasr-add-field-edit-multiset"
                   value="<?php esc_html_e('Add element', 'yet-another-stars-rating'); ?>">

            <input type="submit" value="<?php esc_attr_e('Save changes', 'yet-another-stars-rating') ?>" class="button-primary">

    </form>

    <?php

    die();

} //End function


/****** Validate new multi set form ******/
function yasr_process_new_multi_set_form() {

    if (isset($_POST['multi-set-name'])) {

        global $wpdb;

        if (!current_user_can('manage_options')) {
            wp_die('You are not allowed to be on this page.');
        }

        // Check nonce field
        check_admin_referer('add-multi-set', 'add-nonce-new-multi-set');

        $array_errors = array();
        $error        = false;

        //IF these fields are not empty go ahead
        if ($_POST['multi-set-name'] != '' && $_POST['multi-set-name-element-1'] != '' && $_POST['multi-set-name-element-2'] != '') {

            $multi_set_name = ucfirst(strtolower($_POST['multi-set-name']));

            $multi_set_name_element_ = array();

            $multi_set_name_element_[1] = $_POST['multi-set-name-element-1'];
            $multi_set_name_element_[2] = $_POST['multi-set-name-element-2'];

            //If multi set name is shorter than 3 characher come back
            if (mb_strlen($multi_set_name) < 3 || mb_strlen($multi_set_name_element_[1]) < 3 || mb_strlen($multi_set_name_element_[2]) < 3) {
                $array_errors[] = __('Content field must be longer than 3 chars', 'yet-another-stars-rating');
                $error          = true;
            }


            if (mb_strlen($multi_set_name) > 40 || mb_strlen($multi_set_name_element_[1]) > 40 || mb_strlen($multi_set_name_element_[2]) > 40) {
                $array_errors[] = __('Content field must be shorter than 40 chars', 'yet-another-stars-rating');
                $error          = true;
            }

            //Check if a set with that name already exists
            $check_name_exists = $wpdb->get_results("SELECT set_name FROM " . YASR_MULTI_SET_NAME_TABLE);

            foreach ($check_name_exists as $set_name) {
                if ($multi_set_name == $set_name->set_name) {
                    $array_errors[] = __('You already have a set with this name', 'yet-another-stars-rating');
                    $error          = true;
                }
            }

            $element_filled = 2;

            //If filled get the element from 3 to 9
            for ($i = 3; $i <= 9; $i ++) {

                if (isset($_POST["multi-set-name-element-$i"]) && $_POST["multi-set-name-element-$i"] != '') {

                    $multi_set_name_element_[$i] = $_POST["multi-set-name-element-$i"];

                    if (mb_strlen($multi_set_name_element_[$i]) < 3) {
                        $array_errors[] = sprintf(
                            __('Field # %d must be at least 3 characters', 'yet-another-stars-rating'),
                            $i
                        );
                        $error = true;
                    }

                    if (mb_strlen($multi_set_name_element_[$i]) > 40) {
                        $array_errors[] = sprintf(
                            __('Field # %d must be shorter than 40 characters', 'yet-another-stars-rating'),
                            $i
                        );
                        $error          = true;
                    }

                    $element_filled ++;
                }

            }

            //If there isn't any error write in the table
            if (!$error) {

                $name_table_new_id = false; //avoid undefined

                //get the highest id in table
                $highest_id = $wpdb->get_results("SELECT set_id FROM " . YASR_MULTI_SET_NAME_TABLE . " ORDER BY set_id DESC LIMIT 1 ");

                if (!$highest_id) {
                    $name_table_new_id = 1;
                }

                foreach ($highest_id as $id) {
                    $name_table_new_id = $id->set_id + 1;
                }

                $insert_multi_name_success = $wpdb->replace(
                    YASR_MULTI_SET_NAME_TABLE,
                    array(
                        'set_id'   => $name_table_new_id,
                        'set_name' => $multi_set_name
                    ),
                    array('%d', '%s')
                );

                //If multi set name has been inserted, now we're going to insert elements
                if ($insert_multi_name_success) {
                    $field_table_new_id = false; //avoid undefined
                    $insert_set_value   = false; //avoid undefined

                    //get the highest id in table
                    $highest_id = $wpdb->get_results("SELECT id FROM " . YASR_MULTI_SET_FIELDS_TABLE . " ORDER BY id DESC LIMIT 1 ");

                    if (!$highest_id) {
                        $field_table_new_id = 1;
                    }

                    foreach ($highest_id as $id) {
                        $field_table_new_id = $id->id + 1;
                    }

                    for ($i = 1; $i <= $element_filled; $i ++) {
                        $insert_set_value = $wpdb->replace(
                            YASR_MULTI_SET_FIELDS_TABLE,
                            array(
                                'id'            => $field_table_new_id,
                                'parent_set_id' => $name_table_new_id,
                                'field_name'    => $multi_set_name_element_[$i],
                                'field_id'      => $i
                            ),
                            array('%d', '%d', '%s', '%d')
                        );
                        $field_table_new_id ++; //Avoid overwrite
                    } //End for

                    if ($insert_set_value) {
                        echo "<div class=\"updated\"><p><strong>";
                            esc_html_e('Settings Saved', 'yet-another-stars-rating');
                        echo "</strong></p></div> ";
                    } else {
                        esc_html_e('Something goes wrong trying insert set field name. Please report it',
                            'yet-another-stars-rating');
                    }

                } //End if $insert_multi_name_success

                else {
                    esc_html_e('Something goes wrong trying insert Multi Set name. Please report it',
                        'yet-another-stars-rating');
                }

            } //End if !$error


        }  //End if $_POST['multi-set-name']!=''

        //Else multi set's name and first 2 elements are empty
        else {
            $array_errors[] = __('Multi Set\'s name and first 2 elements can\'t be empty', 'yet-another-stars-rating');
            $error          = true;
        }

        if ($error) {
            return $array_errors;
        }

    } //End if ( isset( $_POST['multi-set-name']) ) {

} //End yasr_process_new_multi_set_form() function

function yasr_process_edit_multi_set_form() {

    $error = false;

    if (isset($_POST['yasr_edit_multi_set_form'])) {

        $set_id = $_POST['yasr_edit_multi_set_form'];
        $number_of_stored_elements = $_POST['yasr-edit-form-number-elements'];

        global $wpdb;

        $array_errors = array();

        if (!current_user_can('manage_options')) {
            wp_die('You are not allowed to be on this page.');
        }

        // Check nonce field
        check_admin_referer('edit-multi-set', 'add-nonce-edit-multi-set');

        //Check if user want to delete entire set
        if (isset($_POST["yasr-remove-multi-set"])) {

            $remove_set = $wpdb->delete(
                YASR_MULTI_SET_NAME_TABLE,
                array(
                    'set_id' => $set_id,
                ),
                array('%d')
            );

            $remove_set_values = $wpdb->delete(
                YASR_MULTI_SET_FIELDS_TABLE,
                array(
                    'parent_set_id' => $set_id,
                ),
                array('%d')
            );

            $remove_set_votes = $wpdb->delete(
                YASR_LOG_MULTI_SET,
                array(
                    'set_type' => $set_id,
                ),
                array('%d')
            );

            if ($remove_set == false) {
                $error          = true;
                $array_errors[] .= __("Something goes wrong trying to delete a Multi Set . Please report it", 'yet-another-stars-rating');
            }

        }

        for ($i = 0; $i <= 9; $i ++) {

            //Than, check if the user want to remove some field
            if (isset($_POST["remove-element-$i"]) && !isset($_POST["yasr-remove-multi-set"])) {

                $field_to_remove = $_POST["remove-element-$i"];

                $remove_field = $wpdb->delete(
                    YASR_MULTI_SET_FIELDS_TABLE,
                    array(
                        'parent_set_id' => $set_id,
                        'field_id'      => $field_to_remove
                    ),
                    array('%d', '%d')
                );

                $remove_values = $wpdb->delete(
                    YASR_LOG_MULTI_SET,
                    array(
                        'set_type' => $set_id,
                        'field_id' => $field_to_remove
                    ),
                    array('%d', '%d')
                );

                if ($remove_field == false) {
                    $error          = true;
                    $array_errors[] = __("Something goes wrong trying to delete a Multi Set's element. Please report it", 'yet-another-stars-rating');
                }


            }  //End if isset $_POST['remove-element-$i']


            //update the stored elements with the new ones
            if (isset($_POST["edit-multi-set-element-$i"]) && !isset($_POST["yasr-remove-multi-set"])
                && !isset($_POST["remove-element-$i"]) && $i <= $number_of_stored_elements) {

                $field_name = $_POST["edit-multi-set-element-$i"];
                $field_id = $_POST["db-id-for-element-$i"];

                //if elements name is shorter than 3 chars
                if (mb_strlen($field_name) < 3) {
                    $array_errors[] = sprintf(
                        __('Field # %d must be at least 3 characters', 'yet-another-stars-rating'),
                        $i);
                    $error = true;
                }

                if (mb_strlen($field_name) > 40) {
                    $array_errors[] = sprintf(
                        __('Field # %d must be shorter than 40 characters', 'yet-another-stars-rating'),
                        $i);
                    $error = true;
                } else {

                    //Check if field name is changed
                    $field_name_in_db = $wpdb->get_results(
                            $wpdb->prepare(
                                    "SELECT field_name FROM "
                                    . YASR_MULTI_SET_FIELDS_TABLE .
                                    " WHERE field_id=%d AND parent_set_id=%d",
                                    $field_id, $set_id));

                    $field_name_in_database = null; //avoid undefined
                    foreach ($field_name_in_db as $field_in_db) {
                        $field_name_in_database = $field_in_db->field_name;
                    }

                    //if field name in db is different from field name in form update it
                    if ($field_name_in_database != $field_name) {

                        $insert_field_name = $wpdb->update(
                            YASR_MULTI_SET_FIELDS_TABLE,

                            array(
                                'field_name' => $field_name,
                            ),
                            array(
                                'parent_set_id' => $set_id,
                                'field_id'      => $field_id
                            ),

                            array('%s'),
                            array('%d', '%d')

                        );

                        if ($insert_field_name == false) {
                            $error          = true;
                            $array_errors[] = __("Something goes wrong trying to update a Multi Set's element. Please report it", 'yet-another-stars-rating');
                        }

                    } //End if ($field_name_in_database != $field_name) {

                }

            } //End if (isset($_POST["edit-multi-set-element-$i"]) && !isset($_POST["remove-element-$i"]) && $i<=$number_of_stored_elements )


            //If $i > number of stored elements, user is adding new elements, so we're going to insert the new ones
            if (isset($_POST["edit-multi-set-element-$i"]) && !isset($_POST["yasr-remove-multi-set"]) && !isset($_POST["remove-element-$i"]) && $i > $number_of_stored_elements) {

                $field_name = $_POST["edit-multi-set-element-$i"];

                //if elements name is shorter than 3 chars return error. I use mb_strlen($field_name) > 1
                //because I don't wont return error if an user add an empty field. An empty field will be
                //just ignored
                if (mb_strlen($field_name) > 1 && mb_strlen($field_name) < 3) {
                    $array_errors[] = sprintf(
                        __('Field # %d must be at least 3 characters', 'yet-another-stars-rating'),
                        $i);
                    $error = true;
                }

                if (mb_strlen($field_name) > 40) {
                    $array_errors[] = sprintf(
                        __('Field # %d must be shorter than 40 characters', 'yet-another-stars-rating'),
                        $i);
                    $error          = true;
                } //if field is not empty
                elseif ($field_name != '') {

                    //from version 2.0.9 id is auto_increment by default, still doing this to compatibility for
                    //existing installs where auto_increment didn't work because set_id=1 alredy exists

                    $field_table_new_id = false; //avoid undefined
                    $new_field_id       = false; //avoid undefined

                    $highest_id = $wpdb->get_results("SELECT id FROM " . YASR_MULTI_SET_FIELDS_TABLE . " ORDER BY id DESC LIMIT 1 ");

                    $highest_field_id = $wpdb->get_results("SELECT field_id FROM " . YASR_MULTI_SET_FIELDS_TABLE . " ORDER BY field_id DESC LIMIT 1 ");

                    foreach ($highest_id as $id) {
                        $field_table_new_id = $id->id + 1;
                    }

                    foreach ($highest_field_id as $id) {
                        $new_field_id = $id->field_id + 1;
                    }

                    $insert_set_value = $wpdb->replace(
                        YASR_MULTI_SET_FIELDS_TABLE,
                        array(
                            'id'            => $field_table_new_id,
                            'parent_set_id' => $set_id,
                            'field_name'    => $field_name,
                            'field_id'      => $new_field_id
                        ),
                        array('%d', '%d', '%s', '%d')
                    );

                    if ($insert_set_value === false) {
                        $error          = true;
                        $array_errors[] = __("Something goes wrong trying to insert set field name in edit form. Please report it", 'yet-another-stars-rating');
                    }

                } //end else
            }

        } //End for

        if ($error) {
            return $array_errors;
        }

        echo "<div class=\"updated\"><p><strong>";
        esc_html_e("Settings Saved", 'yet-another-stars-rating');
        echo "</strong></p></div> ";


    } //End if isset( $_POST['yasr_edit_multi_set_form']


} //End yasr_process_edit_multi_set_form() function


?>