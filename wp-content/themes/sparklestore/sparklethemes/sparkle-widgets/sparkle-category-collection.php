<?php
/**
 ** Adds sparklestore_cat_widget widget.
**/
add_action('widgets_init', 'sparklestore_cat_widget');
function sparklestore_cat_widget() {
    register_widget('sparklestore_cat_widget_area');
}

class sparklestore_cat_widget_area extends WP_Widget {

    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'sparklestore_cat_widget_area', esc_html__('&#9733; Category Collection','sparklestore'), array(
            'description' => esc_html__('A widget that shows WooCommerce multiple category', 'sparklestore')
        ));
    }
    
    private function widget_fields() {

        $taxonomy     = 'product_cat';
        $empty        = 1;
        $orderby      = 'name';  
        $show_count   = 0;      // 1 for yes, 0 for no
        $pad_counts   = 0;      // 1 for yes, 0 for no
        $hierarchical = 1;      // 1 for yes, 0 for no  
        $title        = '';  
        $empty        = 0;
        $args = array(
            'taxonomy'     => $taxonomy,
            'orderby'      => $orderby,
            'show_count'   => $show_count,
            'pad_counts'   => $pad_counts,
            'hierarchical' => $hierarchical,
            'title_li'     => $title,
            'hide_empty'   => $empty
        );

        $title_style = array(
            'layout_one'  => esc_html__('Layout One', 'sparklestore'),
            'layout_two'  => esc_html__('Layout Two', 'sparklestore')
        );

        $display_layout = array(
            'category-style-1'  => esc_html__('Display Style One', 'sparklestore'),
            'category-style-2'  => esc_html__('Display Style Two', 'sparklestore')
        );

        $woocommerce_categories = array();
        $woocommerce_categories_obj = get_categories( $args );
        foreach( $woocommerce_categories_obj as $category ) {
            $woocommerce_categories[$category->term_id] = $category->name;
        }


        $fields = array(

            'block_title_layout' => array(
                'sparklestore_widgets_name'         => 'block_title_layout',
                'sparklestore_widgets_title'        => esc_html__( 'Select Block Title Style', 'sparklestore' ),
                'sparklestore_widgets_default'      => 'layout_one',
                'sparklestore_widgets_field_type'   => 'select',
                'sparklestore_widgets_field_options' => $title_style
            ),

            'sparklestore_main_cat_title' => array(
                'sparklestore_widgets_name' => 'sparklestore_main_cat_title',
                'sparklestore_widgets_title' => esc_html__('Main Title', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'title',
            ),

            'sparklestore_cat_short_desc' => array(
                'sparklestore_widgets_name' => 'sparklestore_cat_short_desc',
                'sparklestore_widgets_title' => esc_html__('Short Description', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'textarea',
                'sparklestore_widgets_row'    => 4,
            ),

            'block_display_style_layout' => array(
                'sparklestore_widgets_name'         => 'block_display_style_layout',
                'sparklestore_widgets_title'        => esc_html__( 'Select Display Style', 'sparklestore' ),
                'sparklestore_widgets_default'      => 'category-style-1',
                'sparklestore_widgets_field_type'   => 'select',
                'sparklestore_widgets_field_options' => $display_layout
            ),
            
            'sparklestore_select_category' => array(
                'sparklestore_widgets_name' => 'sparklestore_select_category',
                'sparklestore_mulicheckbox_title' => esc_html__('Select Category', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'multicheckboxes',
                'sparklestore_widgets_field_options' => $woocommerce_categories
            ),

            'block_display_layout' => array(
                'sparklestore_widgets_name'           => 'block_display_layout',
                'sparklestore_widgets_title'        => esc_html__( 'Display Block Layouts', 'sparklestore' ),
                'sparklestore_widgets_default'      => 'layout1',
                'sparklestore_widgets_field_type'   => 'selector',
                'sparklestore_widgets_field_options' => array(
                    'layout1' => esc_url( get_template_directory_uri() . '/assets/images/layout1.png' ),
                    'layout2' => esc_url( get_template_directory_uri() . '/assets/images/layout2.png' )
                )
            ),
            
        );

        return $fields;
    }

    public function widget($args, $instance) {
        extract($args);
        extract($instance);
        /**
         * wp query for first block
        */  
        $title_layout         = empty( $instance['block_title_layout'] ) ? 'layout_one' : $instance['block_title_layout'];
        $main_title           = empty( $instance['sparklestore_main_cat_title'] ) ?  '' : $instance['sparklestore_main_cat_title'];
        $shot_desc            = empty( $instance['sparklestore_cat_short_desc'] ) ?  '' : $instance['sparklestore_cat_short_desc'];
        $sparklestore_cat_id  = empty( $instance['sparklestore_select_category'] ) ? '' : $instance['sparklestore_select_category'];
        $block_layout         = empty( $instance['block_display_layout'] ) ? 'layout1' : $instance['block_display_layout'];
        $display_style        = empty( $instance['block_display_style_layout'] ) ? 'category-style-1' : $instance['block_display_style_layout'];
        
        echo $before_widget;            
    ?>
        <div class="categoryarea <?php echo esc_attr( $title_layout ); ?>">           
            <div class="container">               
                <div class="categoryarea-wrap <?php echo esc_attr( $block_layout ); ?>">                    
                    
                    <?php if( !empty( $main_title ) ) { ?>
                        <div class="blocktitlewrap <?php echo esc_attr( $block_layout ); ?>">
                            <div class="blocktitle">
                                <?php if( !empty( $main_title ) ) { ?><h2><?php echo esc_html( $main_title ); ?></h2> <?php } ?>
                                
                                <?php if(!empty( $shot_desc )) { ?><p><?php echo esc_html( $shot_desc ); ?></p><?php } ?>
                                
                                <?php if( !empty( $block_layout ) && $block_layout == 'layout1' ){ ?>
                                    <div class="SparkleStoreAction">
                                        <div class="sparkle-lSPrev"></div>
                                        <div class="sparkle-lSNext"></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <ul class="<?php echo esc_attr( $display_style ); ?> <?php if( !empty( $block_layout ) && $block_layout == 'layout1' ){ echo esc_attr( 'categoryslider cS-hidden' ); }else{ echo esc_attr( 'storeproductlist' ); }  ?>">
                        <?php
                            $count = 0; 
                            if(!empty( $sparklestore_cat_id ) ){
                                
                                foreach ($sparklestore_cat_id as $key => $store_cat_id) {          
                                    $thumbnail_id = get_term_meta( $key, 'thumbnail_id', true );
                                    $images = wp_get_attachment_url( $thumbnail_id );
                                    $image = wp_get_attachment_image_src($thumbnail_id, 'sparklestore-cat-collection-image', true);
                                    $term = get_term_by( 'id', $key, 'product_cat');
                                if ( $term && ! is_wp_error( $term ) ) {
                                    $term_link = get_term_link($term);
                                    $term_name = $term->name;
                                    $sub_count =  apply_filters( 'woocommerce_subcategory_count_html', ' ' . $term->count . ' '.esc_html__('Products','sparklestore').'', $term);
                                }else{
                                    $term_link = '#';
                                    $term_name = esc_html__('Category','sparklestore');
                                    $sub_count = '0 '.esc_html__('Products','sparklestore');
                                }
                                
                            $no_img = 'https://via.placeholder.com/285x370';
                        ?>
                            <li class="product-category product">
                                <div class="product-wrapper">
                                    <a href="<?php echo esc_url($term_link); ?>">
                                        <div class="products-cat-wrap">
                                            <div class="products-cat-image">
                                                <?php  
                                                    if ( $images ) {
                                                      echo '<img class="categoryimage" src="' . esc_url( $image[0] ) . '" />';
                                                    } else{
                                                      echo '<img class="categoryimage" src="' . esc_url( $no_img ) . '" />';
                                                    }
                                                ?>
                                            </div>
                                            <div class="products-cat-info">
                                                <h3 class="woocommerce-loop-category__title">
                                                    <?php echo esc_attr($term_name); ?>
                                                    <span class="count"><?php echo esc_html( $sub_count );  ?></span>
                                                </h3>
                                            </div>
                                        </div>
                                    </a>            
                                </div>         
                            </li>
                        <?php } }  ?>
                    </ul>
                </div>
            </div>
        </div>

    <?php         
        echo $after_widget;
    }
   
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $widget_fields = $this->widget_fields();
        foreach ($widget_fields as $widget_field) {
            extract($widget_field);
            $instance[$sparklestore_widgets_name] = sparklestore_widgets_updated_field_value($widget_field, $new_instance[$sparklestore_widgets_name]);
        }
        return $instance;
    }

    public function form($instance) {
        $widget_fields = $this->widget_fields();
        foreach ($widget_fields as $widget_field) {
            extract($widget_field);
            $sparklestore_widgets_field_value = !empty($instance[$sparklestore_widgets_name]) ? $instance[$sparklestore_widgets_name] : '';
            sparklestore_widgets_show_widget_field($this, $widget_field, $sparklestore_widgets_field_value);
        }
    }
}