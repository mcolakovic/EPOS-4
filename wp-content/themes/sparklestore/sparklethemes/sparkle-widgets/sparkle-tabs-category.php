<?php
/**
 * Adds sparklestore_cat_collection_tabs_widget widget.
*/
add_action('widgets_init', 'sparklestore_cat_collection_tabs_widget');
function sparklestore_cat_collection_tabs_widget() {
   register_widget('sparklestore_cat_collection_tabs_widget_area');
}

class sparklestore_cat_collection_tabs_widget_area extends WP_Widget {

   /**
    * Register widget with WordPress.
   **/
   public function __construct() {
       parent::__construct(
           'sparklestore_cat_collection_tabs_widget_area', esc_html__('&#9733; Category Tabs','sparklestore'), array(
           'description' => esc_html__('A widget that display multiple selected category in tabs format related products', 'sparklestore')
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
 
        $woocommerce_categories = array();
        $woocommerce_categories_obj = get_categories($args);
        foreach ($woocommerce_categories_obj as $category) {
         $woocommerce_categories[$category->term_id] = $category->name;
        }

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

            'sparklestore_select_category' => array(
               'sparklestore_widgets_name' => 'sparklestore_select_category',
               'sparklestore_mulicheckbox_title' => esc_html__('Select Category Tabs', 'sparklestore'),
               'sparklestore_widgets_field_type' => 'multicheckboxes',
               'sparklestore_widgets_field_options' => $woocommerce_categories
            ),

            'sparklestore_pro_number_products' => array(
               'sparklestore_widgets_name' => 'sparklestore_pro_number_products',
               'sparklestore_widgets_title' => esc_html__('Enter the Number Products Display', 'sparklestore'),
               'sparklestore_widgets_field_type' => 'number',
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
        $block_layout     = empty( $instance['block_display_layout'] ) ? 'layout1' : $instance['block_display_layout'];

        $sparklestore_cat_id = $instance['sparklestore_select_category'];

        if(!empty( $sparklestore_cat_id )) {
           $first_cat_id =  key( $sparklestore_cat_id );
        }

        $product_number = empty( $instance['sparklestore_pro_number_products'] ) ? 5 : $instance['sparklestore_pro_number_products'];
       
       echo $before_widget;            
   ?>

       <div class="sparkletabsproductwrap <?php echo esc_attr( $title_layout ); ?>">           
           <div class="container"> 
              <div class="categoryarea-wrap <?php echo esc_attr( $block_layout ); ?>"> 

                    <div class="sparkletabs blocktitlewrap <?php echo esc_attr( $block_layout ); ?>">
                    
                        <?php if( !empty( $title_layout ) && $title_layout =='layout_one' ){ if(!empty( $title )) { ?>
                            <div class="blocktitle">
                                <?php if(!empty( $title )) { ?><h2><?php echo esc_html( $title ); ?></h2><?php } ?>
                                <?php if(!empty( $shot_desc )) { ?><p><?php echo esc_html( $shot_desc ); ?></p><?php } ?>
                            </div>
                        <?php } } ?>

                        <ul class="sparkletablinks" data-id="<?php echo intval( $product_number ); ?>" data-layout="<?php echo esc_attr( $block_layout ); ?>">
                            <?php
                                if(!empty($sparklestore_cat_id)){
                                    foreach ($sparklestore_cat_id as $key => $storecat_id) {
                                        $term = get_term_by( 'id', $key, 'product_cat');
                                        if(!$term) continue;
                                    ?>
                                        <li><a href="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
                                    <?php
                                    }
                                }
                            ?>
                        </ul>
                        <?php if( $title_layout =='layout_two' && $block_layout == 'layout1' ){ ?>
                            <div class="SparkleStoreAction">
                                <div class="sparkle-lSPrev"></div>
                                <div class="sparkle-lSNext"></div>
                            </div>
                        <?php } ?>

                    </div>

               <div class="sparkletablinkscontent clearfix">

                   <div class="preloader" style="display:none;">
                       <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/rhombus.gif">
                   </div>

                   <div class="tabscontentwrap">                   
                       <div class="sparkletabproductarea">                           
                           <ul class="<?php if( !empty( $block_layout ) && $block_layout == 'layout1' ){ echo esc_attr( 'tabsproduct cS-hidden' ); }else{ echo esc_attr( 'storeproductlist' ); }  ?>">                            
                               <?php 
                                   $product_args = array(
                                       'post_type' => 'product',
                                       'tax_query' => array(
                                           array(
                                               'taxonomy'  => 'product_cat',
                                               'field'     => 'term_id', 
                                               'terms'     => $first_cat_id                                                                 
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