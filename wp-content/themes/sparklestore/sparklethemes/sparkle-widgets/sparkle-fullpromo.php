<?php
/**
 ** Adds sparklestore_full_promo widget.
**/
add_action('widgets_init', 'sparklestore_full_promo');
function sparklestore_full_promo() {
    register_widget('sparklestore_full_promo_area');
}
class sparklestore_full_promo_area extends WP_Widget {

    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'sparklestore_full_promo_area', esc_html__('&bull; SP: Full Promo Widget','sparklestore'), array(
            'description' => esc_html__('A widget that promote you busincess', 'sparklestore')
        ));
    }
    
    private function widget_fields() {
       
        $fields = array( 

            'sparklestore_full_promo_page' => array(
                'sparklestore_widgets_name' => 'sparklestore_full_promo_page',
                'sparklestore_widgets_title' => esc_html__('Select Promo Page', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'selectpage'
            ),

            'sparklestore_full_promo_button_text' => array(
                'sparklestore_widgets_name' => 'sparklestore_full_promo_button_text',
                'sparklestore_widgets_title' => esc_html__('Enter First Button Text', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'text',
            ),

            'sparklestore_full_promo_button_link' => array(
                'sparklestore_widgets_name' => 'sparklestore_full_promo_button_link',
                'sparklestore_widgets_title' => esc_html__('Enter First Button Link', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'url',
            ),


            'sparklestore_full_promo_button_one_text' => array(
                'sparklestore_widgets_name' => 'sparklestore_full_promo_button_one_text',
                'sparklestore_widgets_title' => esc_html__('Enter Second Button Text', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'text',
            ),

            'sparklestore_full_promo_button_one_link' => array(
                'sparklestore_widgets_name' => 'sparklestore_full_promo_button_one_link',
                'sparklestore_widgets_title' => esc_html__('Enter Second Button Link', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'url',
            ),

            'block_promo_layout' => array(
                'sparklestore_widgets_name'         => 'block_promo_layout',
                'sparklestore_widgets_title'        => esc_html__( 'Select Block Products Type', 'sparklestore' ),
                'sparklestore_widgets_default'      => 'fullbg',
                'sparklestore_widgets_field_type'   => 'select',
                'sparklestore_widgets_field_options' => array(
                        'fullbg'   => esc_html__('Full Width Background', 'sparklestore'),
                        'boxedbg'   => esc_html__('Boxed Background', 'sparklestore')
                    )
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
        
        $block_promo_id   = empty( $instance['sparklestore_full_promo_page'] ) ? '' : $instance['sparklestore_full_promo_page'];
       
        $button_link      = empty( $instance['sparklestore_full_promo_button_link'] ) ? '' : $instance['sparklestore_full_promo_button_link'];
        $button_text      = empty( $instance['sparklestore_full_promo_button_text'] ) ? '' : $instance['sparklestore_full_promo_button_text'];
        
        $button_one_link  = empty( $instance['sparklestore_full_promo_button_one_link'] ) ? '' : $instance['sparklestore_full_promo_button_one_link'];
        $button_one_text  = empty( $instance['sparklestore_full_promo_button_one_text'] ) ? '' : $instance['sparklestore_full_promo_button_one_text'];
        
        $promo_layout     = empty( $instance['block_promo_layout'] ) ? 'fullbg' : $instance['block_promo_layout'];
        $promo_info       = empty( $instance['sparklestore_promo_info'] ) ? '' : $instance['sparklestore_promo_info'];

        echo $before_widget;
  
             if( !empty( $block_promo_id ) ) {

             $block_promo_page = new WP_Query( 'page_id='.$block_promo_id );

             if( $block_promo_page->have_posts() ) { while( $block_promo_page->have_posts() ) { $block_promo_page->the_post();
             
             $full_promo_image = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full', true );         
        
            if( !empty( $block_promo_layout ) && $block_promo_layout == 'fullbg' ){ ?>

                <div class="fullpromobanner">
                    <div class="header-banner">
                        <div class="banner-img">
                            <img src="<?php echo esc_url( $full_promo_image[0] ); ?>">
                        </div>
                        <?php if($promo_info != 1) { ?>
                            <div class="home-slider-overlay"></div>
                            <div class="promobanner-caption text-center">
                                <div class="proppocaption-wrap">
                                    <h2><?php the_title(); ?></h2>
                                    <?php the_content(); ?>
                                </div>
                                <div class="slider-btn-wrap">
                                    <?php if( !empty( $button_text ) || !empty( $button_link ) ){ ?>

                                        <a class="btn btn-primary" href="<?php echo esc_url( $button_link ) ?>">
                                            <?php echo esc_html( $button_text ); ?>
                                        </a>

                                    <?php } if( !empty( $button_one_text ) || !empty( $button_one_link ) ){ ?>

                                        <a class="btn btn-primary" href="<?php echo esc_url( $button_one_link ) ?>">
                                            <?php echo esc_html( $button_one_text ); ?>
                                        </a>

                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            <?php }else{ ?>
            
                <div class="container">
                    <div class="fullpromobanner">
                        <div class="header-banner">
                            <div class="banner-img">
                                <img src="<?php echo esc_url( $full_promo_image[0] ); ?>">
                            </div>
                            <?php if($promo_info != 1) { ?>
                                <div class="home-slider-overlay"></div>
                                <div class="promobanner-caption text-center">
                                    <div class="proppocaption-wrap">
                                        <h2><?php the_title(); ?></h2>
                                        <?php the_content(); ?>
                                    </div>
                                    <div class="slider-btn-wrap">
                                        <?php if( !empty( $button_text ) || !empty( $button_link ) ){ ?>

                                            <a class="btn btn-primary" href="<?php echo esc_url( $button_link ) ?>">
                                                <?php echo esc_html( $button_text ); ?>
                                            </a>

                                        <?php } if( !empty( $button_one_text ) || !empty( $button_one_link ) ){ ?>

                                            <a class="btn btn-primary" href="<?php echo esc_url( $button_one_link ) ?>">
                                                <?php echo esc_html( $button_one_text ); ?>
                                            </a>

                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            <?php } } } wp_reset_postdata(); }

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