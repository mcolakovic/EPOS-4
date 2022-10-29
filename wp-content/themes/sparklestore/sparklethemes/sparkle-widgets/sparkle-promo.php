<?php
/**
 * Adds sparklestore_promo_pages widget.
*/
add_action('widgets_init', 'sparklestore_promo_pages');
function sparklestore_promo_pages() {
    register_widget('sparklestore_promo_pages_area');
}
class sparklestore_promo_pages_area extends WP_Widget {
    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'sparklestore_promo_pages_area', esc_html__('&bull; SP: Promo Widget','sparklestore'), array(
            'description' => esc_html__('A widget that promote your busincess visual way', 'sparklestore')
        ));
    }
    
    private function widget_fields() {
             
        
        $fields = array(

            'sparklestore_promo_layout' => array(
                'sparklestore_widgets_name'         => 'sparklestore_promo_layout',
                'sparklestore_widgets_title'        => esc_html__( 'Select Promo Display Layout', 'sparklestore' ),
                'sparklestore_widgets_default'      => 'onetothree',
                'sparklestore_widgets_field_type'   => 'select',
                'sparklestore_widgets_field_options' => array(
                        'onetothree' => esc_html__('1:3 (Layout)','sparklestore'),
                        'twototwo'   => esc_html__('2:2 (Layout)','sparklestore'),
                        'onetotwo'   => esc_html__('1:2 (Layout)','sparklestore'),
                        'twotoone'   => esc_html__('2:1 (Layout)','sparklestore'),
                    )
            ),         
            
            'banner_start_group_left' => array(
                'sparklestore_widgets_name' => 'banner_start_group_left',
                'sparklestore_widgets_title' => esc_html__('Promo Section One', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'group_start',
            ),

            'sparklestore_promo_one' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_one',
                'sparklestore_widgets_title' => esc_html__('Select Promo Page', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'selectpage'
            ),
           
            'sparklestore_promo_one_button_link' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_one_button_link',
                'sparklestore_widgets_title' => esc_html__('Promo One Button Link', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'url',
            ),
            
            'banner_end_group_left' => array(
                'sparklestore_widgets_name' => 'banner_end_group_left',
                'sparklestore_widgets_field_type' => 'group_end',
            ),
            
            // Promo two Area
            
            'banner_start_group_left_two' => array(
                'sparklestore_widgets_name' => 'banner_start_group_left_two',
                'sparklestore_widgets_title' => esc_html__('Promo Section Two', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'group_start',
            ),
            
            'sparklestore_promo_two' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_two',
                'sparklestore_widgets_title' => esc_html__('Select Promo Page', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'selectpage'
            ),
           
            'sparklestore_promo_two_button_link' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_two_button_link',
                'sparklestore_widgets_title' => esc_html__('Promo Two Button Link', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'url',
            ),
            
            'banner_end_group_left_two' => array(
                'sparklestore_widgets_name' => 'banner_end_group_left_two',
                'sparklestore_widgets_field_type' => 'group_end',
            ),
            
            // Promo three Area

            'banner_start_group_left_three' => array(
                'sparklestore_widgets_name' => 'banner_start_group_left_three',
                'sparklestore_widgets_title' => esc_html__('Promo Section Three', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'group_start',
            ),
            
            'sparklestore_promo_three' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_three',
                'sparklestore_widgets_title' => esc_html__('Select Promo Page', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'selectpage'
            ),       
           
            'sparklestore_promo_three_button_link' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_three_button_link',
                'sparklestore_widgets_title' => esc_html__('Promo Three Button Link', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'url',
            ),

            'banner_end_group_left_three' => array(
                'sparklestore_widgets_name' => 'banner_end_group_left_three',
                'sparklestore_widgets_field_type' => 'group_end',
            ),

            'sparklestore_promo_info' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_info',
                'sparklestore_widgets_title' => esc_html__('Check to Disable Promo Information', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'checkbox',
            ),

        );

        return $fields;
    }

    public function widget($args, $instance) {
        extract($args);
        extract($instance);
        
        $promo_one               = empty( $instance['sparklestore_promo_one'] ) ? '' : $instance['sparklestore_promo_one'];
        $promo_one_button_link   = empty( $instance['sparklestore_promo_one_button_link'] ) ? '' : $instance['sparklestore_promo_one_button_link'];
        $promo_two               = empty( $instance['sparklestore_promo_two'] ) ? '' : $instance['sparklestore_promo_two'];
        $promo_two_button_link   = empty( $instance['sparklestore_promo_two_button_link'] ) ? '' : $instance['sparklestore_promo_two_button_link'];
        $promo_three             = empty( $instance['sparklestore_promo_three'] ) ? '' : $instance['sparklestore_promo_three'];
        $promo_three_button_link = empty( $instance['sparklestore_promo_three_button_link'] ) ? '' : $instance['sparklestore_promo_three_button_link'];
        $promo_layout            = empty( $instance['sparklestore_promo_layout'] ) ? 'onetothree' : $instance['sparklestore_promo_layout'];
        $promo_info         = empty( $instance['sparklestore_promo_info'] ) ? '' : $instance['sparklestore_promo_info'];

        echo $before_widget; 
    ?>
        <div class="promosection <?php echo esc_attr( $promo_layout ); ?>">            
            <div class="container">
                <div class="promoarea-div">
                
                    <?php
                        if( !empty( $promo_one ) ) {
                        $promo_one = new WP_Query( 'page_id='.$promo_one );
                        if( $promo_one->have_posts() ) { while( $promo_one->have_posts() ) { $promo_one->the_post();
                        $promo_one_image = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full', true );         
                    ?>
                    <div class="promoone">  
                        <div class="promoarea">
                            <a href="<?php echo esc_url( $promo_one_button_link ); ?>" class="promo-banner-img">
                                <figure class="promoimage">
                                    <img src="<?php echo esc_url( $promo_one_image[0] ); ?>">
                                </figure>
                            </a>
                            <?php if($promo_info != 1) { ?>
                                <div class="textwrap">
                                    <span></span>
                                    <h2><?php the_title(); ?></h2>
                                </div>
                            <?php } ?>
                        </div> 
                    </div>                     
                    <?php } } wp_reset_postdata(); } ?>
                  
                    
                    <?php
                        if( !empty( $promo_two ) ) {
                        $promo_two = new WP_Query( 'page_id='.$promo_two );
                         if( $promo_two->have_posts() ) { while( $promo_two->have_posts() ) { $promo_two->the_post();
                        $promo_two_image = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full', true );         
                    ?>
                    <div class="promotwo">
                        <div class="promoarea">
                            <a href="<?php echo esc_url( $promo_two_button_link ); ?>" class="promo-banner-img">
                                <figure class="promoimage">
                                    <img src="<?php echo esc_url( $promo_two_image[0] ); ?>">
                                </figure>
                            </a>
                            <?php if($promo_info != 1) { ?>
                                <div class="textwrap">
                                    <span></span>
                                    <h2><?php the_title(); ?></h2>
                                </div>
                            <?php } ?>
                        </div>
                    </div>                        
                    <?php } } wp_reset_postdata(); } ?>
                    
                    <?php
                        if( !empty( $promo_three ) ) {
                        $promo_three = new WP_Query( 'page_id='.$promo_three );
                        if( $promo_three->have_posts() ) { while( $promo_three->have_posts() ) { $promo_three->the_post();
                        $promo_three_image = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full', true );         
                    ?>  
                    <div class="promothree">
                        <div class="promoarea">
                            <a href="<?php echo esc_url( $promo_three_button_link ); ?>" class="promo-banner-img">
                                <figure class="promoimage">
                                    <img src="<?php echo esc_url( $promo_three_image[0] ); ?>">
                                </figure>
                            </a>
                            <?php if($promo_info != 1) { ?>
                                <div class="textwrap">
                                    <span></span>
                                    <h2><?php the_title(); ?></h2>
                                </div>
                            <?php } ?>
                        </div>
                    </div>                       
                    <?php } } wp_reset_postdata(); } ?>
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