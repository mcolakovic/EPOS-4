<?php
/**
 ** Adds sparklestore_cat_with_product_widget widget.
**/
add_action('widgets_init', 'sparklestore_cat_with_product_widget');
function sparklestore_cat_with_product_widget() {
    register_widget('sparklestore_cat_with_product_widget_area');
}
class sparklestore_cat_with_product_widget_area extends WP_Widget {

    /**
     * Register widget with WordPress.
    */
    public function __construct() {
        parent::__construct(
            'sparklestore_cat_with_product_widget_area', esc_html__('&#9733; Category With Product','sparklestore'), array(
            'description' => esc_html__('A widget that shows woocommerce category feature image with selected category products', 'sparklestore')
        ));
    }
    
    private function widget_fields() {
        
          $prod_type = array(
            'rightalign' => esc_html__('Right Align Category Image', 'sparklestore'),
            'leftalign' => esc_html__('Left Align Category Image', 'sparklestore'),
          );

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

          $woocommerce_categories = array();
          $woocommerce_categories_obj = get_categories($args);
          $woocommerce_categories[''] = esc_html__('Select Product Category','sparklestore');
          foreach ($woocommerce_categories_obj as $category) {
            $woocommerce_categories[$category->term_id] = $category->name;
          }

        $title_style = array(
            'layout_one'  => esc_html__('Layout One', 'sparklestore'),
            'layout_two'  => esc_html__('Layout Two', 'sparklestore')
        );

        $fields = array( 
            
            'block_title_layout' => array(
                'sparklestore_widgets_name'          => 'block_title_layout',
                'sparklestore_widgets_title'         => esc_html__( 'Select Block Title Style', 'sparklestore' ),
                'sparklestore_widgets_default'       => 'layout_one',
                'sparklestore_widgets_field_type'    => 'select',
                'sparklestore_widgets_field_options' => $title_style
            ),

            'sparklestore_cat_product_title' => array(
                'sparklestore_widgets_name' => 'sparklestore_cat_product_title',
                'sparklestore_widgets_title' => esc_html__('Title', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'title',
            ),
            'sparklestore_cat_product_short_desc' => array(
                'sparklestore_widgets_name' => 'sparklestore_cat_product_short_desc',
                'sparklestore_widgets_title' => esc_html__('Very Short Description', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'textarea',
                'sparklestore_widgets_row'    => 4,
            ),
            'sparklestore_cat_image_aligment' => array(
                'sparklestore_widgets_name' => 'sparklestore_cat_image_aligment',
                'sparklestore_widgets_title' => esc_html__('Select Display Style (Image Alignment)', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'select',
                'sparklestore_widgets_field_options' => $prod_type
            ),
            'sparklestore_woo_category' => array(
                'sparklestore_widgets_name' => 'sparklestore_woo_category',
                'sparklestore_widgets_title' => esc_html__('Select Product Category', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'select',
                'sparklestore_widgets_field_options' => $woocommerce_categories
            ),
            'sparklestore_cat_product_number' => array(
                'sparklestore_widgets_name' => 'sparklestore_cat_product_number',
                'sparklestore_widgets_title' => esc_html__('Enter Number of Products Show', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'number',
            ),
            'sparklestore_cat_cat_product_info' => array(
                'sparklestore_widgets_name' => 'sparklestore_cat_cat_product_info',
                'sparklestore_widgets_title' => esc_html__('Checked to Display Category Info', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'checkbox',
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
        $title            = empty( $instance['sparklestore_cat_product_title'] ) ? '' : $instance['sparklestore_cat_product_title']; 
        $shot_desc        = empty( $instance['sparklestore_cat_product_short_desc'] ) ? '' : $instance['sparklestore_cat_product_short_desc'];
        $cat_aligment     = empty( $instance['sparklestore_cat_image_aligment'] ) ? 'rightalign' : $instance['sparklestore_cat_image_aligment'];
        $product_category = empty( $instance['sparklestore_woo_category'] ) ? '' : $instance['sparklestore_woo_category'];
        $product_number   = empty( $instance['sparklestore_cat_product_number'] ) ? 5 : $instance['sparklestore_cat_product_number'];
        $cat_info         = empty( $instance['sparklestore_cat_cat_product_info'] ) ? '' : $instance['sparklestore_cat_cat_product_info'];

        if( !empty( $product_category ) ){
          $cat_id = get_term($product_category,'product_cat');

          $category_id = $cat_id ? $cat_id->term_id : '';
          $category_link = get_term_link( $category_id,'product_cat' );
          if(is_wp_error($category_link)){
            $category_link = get_permalink( wc_get_page_id( 'shop' ) );
          }
        }
        else{
          $category_link = get_permalink( wc_get_page_id( 'shop' ) );
        } 

        echo $before_widget; 
    ?>  

    <div class="categorproducts <?php echo esc_attr( $title_layout ); ?>">
        <div class="container">                
            <div class="categoryarea-wrap">                
                <div id="categoryproductslider" class="categoryproductslider <?php echo esc_attr( $cat_aligment ); ?>">
                    <?php if(!empty( $title )) { ?>    
                        <div class="blocktitlewrap">
                            <div class="blocktitle">
                                <?php if(!empty( $title )) { ?><h2><?php echo esc_html( $title ); ?></h2><?php } ?>
                                <?php if(!empty( $shot_desc )) { ?><p><?php echo esc_html( $shot_desc ); ?></p><?php } ?>
                                <div class="SparkleStoreAction">
                                    <div class="sparkle-lSPrev"></div>
                                    <div class="sparkle-lSNext"></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="categoryproductwrap">
                        <div class="homeblockinner"> 
                            <?php 
                                $taxonomy = 'product_cat';                                
                                $terms = term_description( $product_category, $taxonomy );
                                $terms_name = get_term( $product_category, $taxonomy );
                                $thumbnail_id = get_term_meta( $product_category, 'thumbnail_id', true);
                                if ( !is_wp_error($thumbnail_id) && !empty( $thumbnail_id ) ) {
                                  $image = wp_get_attachment_image_src($thumbnail_id, 'shop_single');
                                } else{ 
                                    $no_image = 'https://via.placeholder.com/285x370';
                                }  
                            ?>
                            <div class="catblockwrap" <?php  if ( !is_wp_error($thumbnail_id) && !empty($thumbnail_id)) {  ?>style="background-image:url(<?php echo esc_url($image[0]); ?>);"<?php } else{ ?>style="background-image:url(<?php echo esc_url($no_image); ?>);"<?php } ?>>
                                <div class="block-title-desc">                                
                                    <div class="table-outer">
                                        <div class="table-inner">
                                            <?php if( !is_wp_error($terms_name) && !empty( $terms_name->name ) ) { ?><h2><a href="<?php echo esc_url( $category_link ); ?>"><?php echo esc_html( $terms_name->name ); ?></a></h2><?php } ?>
                                            <?php echo esc_attr( $terms ); ?>
                                            <a href="<?php echo esc_url($category_link); ?>" class="btn btn-primary"><?php esc_html_e('Shop Now','sparklestore'); ?></a>
                                        </div>
                                    </div>                        
                                </div>
                                <a href="<?php echo esc_url($category_link); ?>" class="sparkle-overlay"></a>                        
                            </div>                        
                        </div>
                        <div class="productwrap">
                            <ul class="catwithproduct cS-hidden">                        
                                <?php 
                                    $product_args = array(
                                        'post_type' => 'product',
                                        'tax_query' => array(
                                            array(
                                                'taxonomy'  => 'product_cat',
                                                'field'     => 'id', 
                                                'terms'     => $product_category                                                                 
                                            )),
                                        'posts_per_page' => $product_number
                                    );
                                    $query = new WP_Query($product_args);

                                    if($query->have_posts()) { while($query->have_posts()) { $query->the_post();
                                ?>
                                    <?php wc_get_template_part( 'content', 'product' ); ?>
                                    
                                <?php } } wp_reset_postdata(); ?>                          
                            </ul> 
                        </div>                   
                    </div>
                </div>
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