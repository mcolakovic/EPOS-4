<?php
/**
 * Adds sparklestore_new_promo_area widget.
*/
add_action('widgets_init', 'sparklestore_new_promo_area');
function sparklestore_new_promo_area() {
    register_widget('sparklestore_new_promo_widget_area');
}
class sparklestore_new_promo_widget_area extends WP_Widget {
    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'sparklestore_new_promo_widget_area', esc_html__('&bull; SP: New Promo Widget','sparklestore'), array(
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

            'sparklestore_promo_image_one' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_image_one',
                'sparklestore_widgets_title' => esc_html__('Upload Promo One Image', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'upload',
            ),

            'sparklestore_promo_block_one_title' => array(
                'sparklestore_widgets_name'         => 'sparklestore_promo_block_one_title',
                'sparklestore_widgets_title'        => esc_html__( 'Enter Promo Block One Title', 'sparklestore' ),
                'sparklestore_widgets_field_type'   => 'text'
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
            
            'sparklestore_promo_image_two' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_image_two',
                'sparklestore_widgets_title' => esc_html__('Upload Promo Two Image', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'upload',
            ),

            'sparklestore_promo_block_two_title' => array(
                'sparklestore_widgets_name'         => 'sparklestore_promo_block_two_title',
                'sparklestore_widgets_title'        => esc_html__( 'Enter Promo Block Two Title', 'sparklestore' ),
                'sparklestore_widgets_field_type'   => 'text'
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

            'sparklestore_promo_image_three' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_image_three',
                'sparklestore_widgets_title' => esc_html__('Upload Promo Three Image', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'upload',
            ),

            'sparklestore_promo_block_three_title' => array(
                'sparklestore_widgets_name'         => 'sparklestore_promo_block_three_title',
                'sparklestore_widgets_title'        => esc_html__( 'Enter Promo Block Three Title', 'sparklestore' ),
                'sparklestore_widgets_field_type'   => 'text'
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

        $promo_one_img     =  empty( $instance['sparklestore_promo_image_one'] ) ? '' : $instance['sparklestore_promo_image_one'];
        $promo_one_title   =  empty( $instance['sparklestore_promo_block_one_title'] ) ? '' : $instance['sparklestore_promo_block_one_title'];
        $promo_one_url     =  empty( $instance['sparklestore_promo_one_button_link'] ) ? '' : $instance['sparklestore_promo_one_button_link'];
        
        $promo_two_img     =  empty( $instance['sparklestore_promo_image_two'] ) ? '' : $instance['sparklestore_promo_image_two'];
        $promo_two_title   =  empty( $instance['sparklestore_promo_block_two_title'] ) ? '' : $instance['sparklestore_promo_block_two_title'];
        $promo_two_url     =  empty( $instance['sparklestore_promo_two_button_link'] ) ? '' : $instance['sparklestore_promo_two_button_link'];

        $promo_three_img   =  empty( $instance['sparklestore_promo_image_three'] ) ? '' : $instance['sparklestore_promo_image_three'];
        $promo_three_title =  empty( $instance['sparklestore_promo_block_three_title'] ) ? '' : $instance['sparklestore_promo_block_three_title'];
        $promo_three_url   =  empty( $instance['sparklestore_promo_three_button_link'] ) ? '' : $instance['sparklestore_promo_three_button_link'];
        
        $promo_layout      = empty( $instance['sparklestore_promo_layout'] ) ? 'onetothree' : $instance['sparklestore_promo_layout'];
        $promo_info         = empty( $instance['sparklestore_promo_info'] ) ? '' : $instance['sparklestore_promo_info'];

        echo $before_widget; ?>

        <div class="promosection <?php echo esc_attr( $promo_layout ); ?>">            
            <div class="container">
                <div class="promoarea-div">
                    <div class="promoone">  
                        <?php if( !empty( $promo_one_img ) ){ ?>                       
                            <div class="promoarea">
                                <a href="<?php echo esc_url( $promo_one_url ); ?>" class="promo-banner-img">
                                    <figure class="promoimage">
                                        <img src="<?php echo esc_url( $promo_one_img ); ?>" alt="<?php echo esc_html( $promo_one_title ); ?>">
                                    </figure>
                                </a>
                                <?php if($promo_info != 1) { if( !empty( $promo_one_title ) ){ ?>
                                    <div class="textwrap">
                                        <span></span>
                                        <h2><?php echo esc_html( $promo_one_title ); ?></h2>
                                    </div>
                                <?php } } ?>
                                
                            </div>
                        <?php } ?>
                    </div>
                    <div class="promotwo">
                        <?php if( !empty( $promo_two_img ) ){ ?>
                            <div class="promoarea">
                                <a href="<?php echo esc_url( $promo_two_url ); ?>" class="promo-banner-img">
                                    <figure class="promoimage">
                                        <img src="<?php echo esc_url( $promo_two_img ); ?>" alt="<?php echo esc_html( $promo_two_title ); ?>">
                                    </figure>
                                </a>
                                <?php if($promo_info != 1) { if( !empty( $promo_two_title ) ){ ?>
                                    <div class="textwrap">
                                        <span></span>
                                        <h2><?php echo esc_html( $promo_two_title ); ?></h2>
                                    </div>
                                <?php } } ?>
                                
                            </div>
                        <?php } ?>
                    </div>            
                    <div class="promothree">                         
                        <?php if( !empty( $promo_three_img ) ){ ?>
                            <div class="promoarea">
                                <a href="<?php echo esc_url( $promo_three_url ); ?>" class="promo-banner-img">
                                    <figure class="promoimage">
                                        <img src="<?php echo esc_url( $promo_three_img ); ?>" alt="<?php echo esc_attr( $promo_three_title ); ?>">
                                    </figure>
                                </a>
                                <?php if($promo_info != 1) { if( !empty( $promo_three_title ) ){ ?>
                                    <div class="textwrap">
                                        <span></span>
                                        <h2><?php echo esc_html( $promo_three_title ); ?></h2>
                                    </div>
                                <?php } } ?>
                                
                            </div> 
                        <?php } ?>                  
                    </div>
                </div>
            </div>
        </div>

      <?php echo $after_widget;
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