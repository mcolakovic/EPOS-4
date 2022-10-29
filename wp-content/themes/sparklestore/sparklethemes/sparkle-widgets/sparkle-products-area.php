<?php
/**
 ** Adds sparklestore_product_widget widget.
**/
add_action('widgets_init', 'sparklestore_product_widget');
function sparklestore_product_widget() {
    register_widget('sparklestore_product_widget_area');
}
class sparklestore_product_widget_area extends WP_Widget {

    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'sparklestore_product_widget_area', esc_html__('&#9733; Product Area','sparklestore'), array(
            'description' => esc_html__('A widget that shows woocommerce type of (Latest, Feature, On Sale, Up Sale) products', 'sparklestore')
        ));
    }
    
    private function widget_fields() {
        

        $prod_type = array(
            ''                => esc_html__('Select Product Type', 'sparklestore'),
            'latest_product'  => esc_html__('Latest Product', 'sparklestore'),
            'upsell_product'  => esc_html__('UpSell Product', 'sparklestore'),
            'feature_product' => esc_html__('Feature Product', 'sparklestore'),
            'on_sale'         => esc_html__('On Sale Product', 'sparklestore'),
        );

        $title_style = array(
            'layout_one'  => esc_html__('Layout One', 'sparklestore'),
            'layout_two'  => esc_html__('Layout Two', 'sparklestore')
        );

        $fields = array( 

            'block_title_layout' => array(
                'sparklestore_widgets_name'         => 'block_title_layout',
                'sparklestore_widgets_title'        => esc_html__( 'Select Block Title Style', 'sparklestore' ),
                'sparklestore_widgets_default'      => 'layout_one',
                'sparklestore_widgets_field_type'   => 'select',
                'sparklestore_widgets_field_options' => $title_style
            ),
            
            'sparklestore_product_title' => array(
                'sparklestore_widgets_name' => 'sparklestore_product_title',
                'sparklestore_widgets_title' => esc_html__('Title', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'title',
            ),
            'sparklestore_product_short_desc' => array(
                'sparklestore_widgets_name' => 'sparklestore_product_short_desc',
                'sparklestore_widgets_title' => esc_html__('Very Short Description', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'textarea',
                'sparklestore_widgets_row'    => 4,
            ),
            'sparklestore_product_type' => array(
                'sparklestore_widgets_name' => 'sparklestore_product_type',
                'sparklestore_widgets_title' => esc_html__('Select Product Type', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'select',
                'sparklestore_widgets_field_options' => $prod_type
            ),

            'sparklestore_product_number' => array(
                'sparklestore_widgets_name' => 'sparklestore_product_number',
                'sparklestore_widgets_title' => esc_html__('Enter Number of Products Show', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'number',
                'sparklestore_widgets_default'      => 5,
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
        $title_layout     = empty( $instance['block_title_layout'] ) ? 'layout_one' : $instance['block_title_layout'];
        $title            = empty( $instance['sparklestore_product_title'] ) ? '' : $instance['sparklestore_product_title']; 
        $shot_desc        = empty( $instance['sparklestore_product_short_desc'] ) ? '' : $instance['sparklestore_product_short_desc'];
        $product_type     = empty( $instance['sparklestore_product_type'] ) ? '' : $instance['sparklestore_product_type'];
        $product_number   = empty( $instance['sparklestore_product_number'] ) ? 5 : $instance['sparklestore_product_number'];
        $block_layout     = empty( $instance['block_display_layout'] ) ? 'layout1' : $instance['block_display_layout'];

        $product_args       =   '';
        global $product_label_custom;
        if($product_type == 'latest_product'){
            $product_label_custom = esc_html__('New', 'sparklestore');
            $product_args = array(
                'post_type' => 'product',
                'posts_per_page' => $product_number
            );
        }

        elseif($product_type == 'upsell_product'){
            $product_args = array(
                'post_type'         => 'product',
                'posts_per_page'    => 10,
                'meta_key'          => 'total_sales',
                'orderby'           => 'meta_value_num',
                'posts_per_page'    => $product_number
            );
        }

        elseif($product_type == 'feature_product'){
            $product_args = array(
                'post_type'        => 'product',  
                'tax_query' => array(
                      'relation' => 'AND',      
                  array(
                      'taxonomy' => 'product_visibility',
                      'field'    => 'name',
                      'terms'    => 'featured',
                      'operator' => 'IN'
                  )
                ), 
                'posts_per_page'   => $product_number   
            );
        }

        elseif($product_type == 'on_sale'){
            $product_args = array(
            'post_type'      => 'product',
            'meta_query'     => array(
                'relation' => 'OR',
                array( // Simple products type
                    'key'           => '_sale_price',
                    'value'         => 0,
                    'compare'       => '>',
                    'type'          => 'numeric'
                ),
                array( // Variable products type
                    'key'           => '_min_variation_sale_price',
                    'value'         => 0,
                    'compare'       => '>',
                    'type'          => 'numeric'
                )
            ));
        }
        
        echo $before_widget; 
    ?> 

        <div class="producttype-wrap <?php echo esc_attr( $title_layout ); ?>">            
            <div class="container">                  
                <div class="categoryarea-wrap <?php echo esc_attr( $block_layout ); ?>"> 
                    <?php if(!empty( $title )) { ?>
                        <div class="blocktitlewrap <?php echo esc_attr( $block_layout ); ?>">                        
                            <div class="blocktitle">
                                <?php if(!empty( $title )) { ?><h2><?php echo esc_html( $title ); ?></h2><?php } ?>
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
                    <ul class="<?php if( !empty( $block_layout ) && $block_layout == 'layout1' ){ echo esc_attr( 'productarea cS-hidden' ); }else{ echo esc_attr( 'storeproductlist' ); }  ?>">
                        <?php 
                            $query = new WP_Query( $product_args );

                            if($query->have_posts()) {  while($query->have_posts()) { $query->the_post();
                        ?>
                            <?php wc_get_template_part( 'content', 'product' ); ?>
                            
                        <?php } } wp_reset_postdata(); unset($GLOBALS['product_label_custom']); ?>                    
                    </ul>
                </div>        
            </div>        
        </div><!-- End Product Slider -->

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