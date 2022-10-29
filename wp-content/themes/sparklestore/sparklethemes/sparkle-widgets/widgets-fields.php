<?php
/**
** Sparkle Store Field Functional file
* @package Sparkle_Store
*/
function sparklestore_widgets_show_widget_field($instance = '', $widget_field = '', $sparklestore_field_value = '') {
   
    // List Category List in array
    $sparklestore_category_list[0] = array(
        'value' => 0,
        'label' => esc_html__('Select Categories','sparklestore')
    );
    $sparklestore_posts = get_categories();
    foreach ( $sparklestore_posts as $sparklestore_post ) :
        $sparklestore_category_list[$sparklestore_post->term_id] = array(
            'value' => $sparklestore_post->term_id,
            'label' => $sparklestore_post->name
        );
    endforeach;

    /**
     * Default Page List in array
    */
    $sparklestore_pagelist[0] = array(
        'value' => 0,
        'label' => esc_html__('Select Pages','sparklestore')
    );
    $arg = array( 'posts_per_page' => -1 );
    $sparklestore_pages = get_pages( $arg );
    foreach ( $sparklestore_pages as $sparklestore_page ) :
        $sparklestore_pagelist[$sparklestore_page->ID] = array(
            'value' => $sparklestore_page->ID,
            'label' => $sparklestore_page->post_title
        );
    endforeach;

    extract($widget_field);

    switch ($sparklestore_widgets_field_type) {

        // Standard text field
        case 'text' :
            ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name ) ); ?>"><?php echo esc_html( $sparklestore_widgets_title ); ?> :</label>
                <input class="widefat" id="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $sparklestore_widgets_name ) ); ?>" type="text" value="<?php echo esc_attr( $sparklestore_field_value ) ; ?>" />

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html( $sparklestore_widgets_description ); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        //title
        case 'title' :
            ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name ) ); ?>"><?php echo esc_html( $sparklestore_widgets_title ); ?> :</label>
                <input class="widefat" id="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $sparklestore_widgets_name ) ); ?>" type="text" value="<?php echo esc_attr( $sparklestore_field_value ) ; ?>" />
                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html( $sparklestore_widgets_description ); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        case 'group_start' :
            ?>
            <div class="sparklestore-main-group" id="ap-font-awesome-list <?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name ) ); ?>">
                <div class="sparklestore-main-group-heading" style="font-size: 15px;  font-weight: bold;  padding-top: 12px;"><?php echo esc_html( $sparklestore_widgets_title ); ?><span class="toogle-arrow"></span></div>
                <div class="sparklestore-main-group-wrap">
            <?php
            break;

            case 'group_end':
            ?></div>
            </div><?php
            break;

        // Standard url field
        case 'url' :
            ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name ) ); ?>"><?php echo esc_html( $sparklestore_widgets_title ); ?> :</label>
                <input class="widefat" id="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name )); ?>" name="<?php echo esc_attr( $instance->get_field_name( $sparklestore_widgets_name )); ?>" type="text" value="<?php echo esc_attr( $sparklestore_field_value ); ?>" />

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html( $sparklestore_widgets_description ); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Textarea field
        case 'textarea' :
            ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name ) ); ?>"><?php echo esc_html( $sparklestore_widgets_title ); ?> :</label>
                <textarea class="widefat" rows="<?php echo esc_attr( $sparklestore_widgets_row ); ?>" id="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name )); ?>" name="<?php echo esc_attr( $instance->get_field_name( $sparklestore_widgets_name )); ?>"><?php echo esc_html( $sparklestore_field_value ); ?></textarea>
            </p>
            <?php
            break;

        // Checkbox field
        case 'checkbox' :
            ?>
            <p>
                <input id="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name )); ?>" name="<?php echo esc_attr( $instance->get_field_name( $sparklestore_widgets_name )); ?>" type="checkbox" value="1" <?php checked('1', $sparklestore_field_value); ?>/>
                <label for="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name )); ?>"><?php echo esc_html( $sparklestore_widgets_title ); ?></label>

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html( $sparklestore_widgets_description ); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Radio fields
        case 'radio' :
            ?>
            <p>
                <?php
                echo esc_html( $sparklestore_widgets_title );
                echo '<br />';
                foreach ($sparklestore_widgets_field_options as $sparklestore_option_name => $sparklestore_option_title) {
                    ?>
                    <input id="<?php echo esc_attr( $instance->get_field_id( $sparklestore_option_name )); ?>" name="<?php echo esc_attr( $instance->get_field_name( $sparklestore_widgets_name )); ?>" type="radio" value="<?php echo esc_attr( $sparklestore_option_name ); ?>" <?php checked($sparklestore_option_name, $sparklestore_field_value); ?> />
                    <label for="<?php echo esc_attr( $instance->get_field_id( $sparklestore_option_name )); ?>"><?php echo esc_html( $sparklestore_option_title ); ?></label>
                    <br />
                <?php } ?>

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <small><?php echo esc_html( $sparklestore_widgets_description ); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Select field
        case 'select' :
            ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name )); ?>"><?php echo esc_html( $sparklestore_widgets_title ); ?> :</label>
                <select name="<?php echo esc_attr( $instance->get_field_name( $sparklestore_widgets_name )); ?>" id="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name )); ?>" class="widefat">
                    <?php foreach ($sparklestore_widgets_field_options as $sparklestore_option_name => $sparklestore_option_title) { ?>
                        <option value="<?php echo esc_attr( $sparklestore_option_name ); ?>" id="<?php echo esc_attr( $instance->get_field_id($sparklestore_option_name)); ?>" <?php selected($sparklestore_option_name, $sparklestore_field_value); ?>><?php echo esc_html( $sparklestore_option_title ); ?></option>
                    <?php } ?>
                </select>

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html( $sparklestore_widgets_description ); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Select Pages field
        case 'selectpage' :
            ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id($sparklestore_widgets_name)); ?>"><?php echo esc_html( $sparklestore_widgets_title ); ?>:</label>
                <select name="<?php echo esc_attr( $instance->get_field_name($sparklestore_widgets_name)); ?>" id="<?php echo esc_attr($instance->get_field_id($sparklestore_widgets_name)); ?>" class="widefat">
                    <?php foreach ($sparklestore_pagelist as $sparklestore_page) { ?>
                        <option value="<?php echo esc_attr($sparklestore_page['value']); ?>" id="<?php echo esc_attr($instance->get_field_id($sparklestore_page['label'])); ?>" <?php selected( $sparklestore_page['value'], $sparklestore_field_value ); ?>><?php echo esc_html( $sparklestore_page['label'] ); ?></option>
                    <?php } ?>
                </select>

                <?php if ( isset( $sparklestore_widgets_description ) ) { ?>
                    <br />
                    <small><?php echo esc_html( $sparklestore_widgets_description ); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Number field
        case 'number' :
            ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id($sparklestore_widgets_name) ); ?>"><?php echo esc_html( $sparklestore_widgets_title ); ?> :</label><br />
                <input name="<?php echo esc_attr( $instance->get_field_name($sparklestore_widgets_name) ); ?>" type="number" id="<?php echo esc_attr($instance->get_field_id($sparklestore_widgets_name)); ?>" value="<?php echo esc_attr($sparklestore_field_value); ?>" class="widefat" />

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html( $sparklestore_widgets_description ); ?></small>
                <?php } ?>
            </p>
            <?php
            break;        

        // Select category field
        case 'select_category' :
            ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id($sparklestore_widgets_name) ); ?>"><?php echo esc_html( $sparklestore_widgets_title ); ?> :</label>
                <select name="<?php echo esc_attr( $instance->get_field_name($sparklestore_widgets_name)); ?>" id="<?php echo esc_attr($instance->get_field_id($sparklestore_widgets_name)); ?>" class="widefat">
                    <?php foreach ($sparklestore_category_list as $sparklestore_single_post) { ?>
                        <option value="<?php echo esc_attr($sparklestore_single_post['value']); ?>" id="<?php echo esc_attr($instance->get_field_id($sparklestore_single_post['label'])); ?>" <?php selected($sparklestore_single_post['value'], $sparklestore_field_value); ?>><?php echo esc_html( $sparklestore_single_post['label'] ); ?></option>
                    <?php } ?>
                </select>

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html( $sparklestore_widgets_description ); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        //Multi checkboxes
        case 'multicheckboxes' :
            
            if( isset( $sparklestore_mulicheckbox_title ) ) { ?>
                <label><?php echo esc_html( $sparklestore_mulicheckbox_title ); ?>:</label>
            <?php }
            echo '<div class="sparklestore-multiplecat">';
                foreach ( $sparklestore_widgets_field_options as $sparklestore_option_name => $sparklestore_option_title) {
                    if( isset( $sparklestore_field_value[$sparklestore_option_name] ) ) {
                        $sparklestore_field_value[$sparklestore_option_name] = 1;
                    }else{
                        $sparklestore_field_value[$sparklestore_option_name] = 0;
                    }                
                ?>
                    <p>
                        <input id="<?php echo esc_attr($instance->get_field_id($sparklestore_widgets_name)); ?>" name="<?php echo esc_attr($instance->get_field_name($sparklestore_widgets_name).'['.$sparklestore_option_name.']'); ?>" type="checkbox" value="1" <?php checked('1', $sparklestore_field_value[$sparklestore_option_name]); ?>/>
                        <label for="<?php echo esc_attr($instance->get_field_id($sparklestore_option_name)); ?>"><?php echo esc_html( $sparklestore_option_title ); ?></label>
                    </p>
                <?php
                    }
            echo '</div>';
                if (isset($sparklestore_widgets_description)) {
            ?>
                    <small><em><?php echo esc_html( $sparklestore_widgets_description ); ?></em></small>
            <?php
                }
            
        break;

        // Upload field
        case 'upload':
            $image = $image_class = "";
            if( $sparklestore_field_value ){ 
                $image = '<img src="'.esc_url( $sparklestore_field_value ).'" style="max-width:100%;"/>';    
                $image_class = ' hidden';
            }
            ?>
                <div class="attachment-media-view">

                    <p><span class="field-label"><label for="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name ) ); ?>"><?php echo esc_html( $sparklestore_widgets_title ); ?>:</label></span></p>
                    
                        <div class="placeholder<?php echo esc_attr( $image_class ); ?>">
                            <?php esc_html_e( 'No image selected', 'sparklestore' ); ?>
                        </div>
                        <div class="thumbnail thumbnail-image">
                            <?php echo wp_kses_post ( $image ); ?>
                        </div>

                        <div class="actions clearfix">
                            <button type="button" class="button sparklestore-delete-button align-left"><?php esc_html_e( 'Remove', 'sparklestore' ); ?></button>
                            <button type="button" class="button sparklestore-upload-button alignright"><?php esc_html_e( 'Select Image', 'sparklestore' ); ?></button>
                            
                            <input name="<?php echo esc_attr( $instance->get_field_name( $sparklestore_widgets_name ) ); ?>" id="<?php echo esc_attr( $instance->get_field_id( $sparklestore_widgets_name ) ); ?>" class="upload-id" type="hidden" value="<?php echo esc_url( $sparklestore_field_value ) ?>"/>
                        </div>

                    <?php if ( isset( $sparklestore_widgets_description ) ) { ?>
                        <br />
                        <em><?php echo wp_kses_post( $sparklestore_widgets_description ); ?></em>
                    <?php } ?>

                </div><!-- .attachment-media-view -->
            <?php
        break;

        /**
         * Selector field
        */
        case 'selector':
            if( empty( $sparklestore_field_value ) ) {
                $sparklestore_field_value = $sparklestore_widgets_default;
            } ?>

            <p><span class="field-label"><label class="field-title"><?php echo esc_html( $sparklestore_widgets_title ); ?></label></span></p>
            <?php            
                echo '<div class="selector-labels">';
                foreach ( $sparklestore_widgets_field_options as $option => $val ){
                    $class = ( $sparklestore_field_value == $option ) ? 'selector-selected': '';
                    echo '<label class="'. esc_attr( $class ).'" data-val="'.esc_attr( $option ).'">';
                    echo '<img src="'.esc_url( $val ).'"/>';
                    echo '</label>'; 
                }
                echo '</div>';
                echo '<input data-default="'.esc_attr( $sparklestore_field_value ).'" type="hidden" value="'.esc_attr( $sparklestore_field_value ).'" name="'.esc_attr( $instance->get_field_name( $sparklestore_widgets_name ) ).'"/>';
        break;
    }
}

function sparklestore_widgets_updated_field_value($widget_field, $new_field_value) {

    extract($widget_field);

    if ($sparklestore_widgets_field_type == 'number') {

        return absint($new_field_value);

    } elseif ($sparklestore_widgets_field_type == 'textarea') {
        
        if (!isset($sparklestore_widgets_allowed_tags)) {
            $sparklestore_widgets_allowed_tags = '<p><strong><em><a><br>';
        }

        return wp_kses_post($new_field_value, $sparklestore_widgets_allowed_tags);
    } 
    elseif ($sparklestore_widgets_field_type == 'url') {
        return esc_url_raw($new_field_value);
    }
    elseif ($sparklestore_widgets_field_type == 'title') {
        return wp_kses_post($new_field_value);
    }
    elseif ($sparklestore_widgets_field_type == 'multicheckboxes') {
        return $new_field_value;
    }
    else {
        return wp_kses_post($new_field_value);
    }
}



/**
 * Load about section widget area file.
*/
require get_template_directory().'/sparklethemes/sparkle-widgets/sparkle-promo.php';
require get_template_directory().'/sparklethemes/sparkle-widgets/sparkle-newpromo.php';


/**
 * Load Full Promo widget area file.
*/
require get_template_directory().'/sparklethemes/sparkle-widgets/sparkle-fullpromo.php';


/**
 * Load Blogs Posts widget area file.
*/
require get_template_directory().'/sparklethemes/sparkle-widgets/sparkle-blogs-widget.php';


if (sparklestore_is_woocommerce_activated()) {
    
    /**
     * Load products widget area file.
    */
    require get_template_directory().'/sparklethemes/sparkle-widgets/sparkle-products-area.php';

    /**
     * Load category product widget area file.
    */
    require get_template_directory().'/sparklethemes/sparkle-widgets/sparkle-category-products.php';

    /**
     * Load category collection widget area file.
    */
    require get_template_directory().'/sparklethemes/sparkle-widgets/sparkle-category-collection.php';

    /**
     * Load tabs category products widget area file.
    */
    require get_template_directory().'/sparklethemes/sparkle-widgets/sparkle-tabs-category.php';
}