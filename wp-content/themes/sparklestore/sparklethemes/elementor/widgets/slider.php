<?php
/**
 * StparkleStore Call to action.
 *
 * @package    SparkleThemes
 * @subpackage SparkleStore
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	return; // Exit if it is accessed directly
}

class Sparklestore_banner_slider extends Widget_Base{

	/**
	 * Retrieve Sparklestore_banner_slider widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sparklestore-slider';
	}

	/**
	 * Retrieve Sparklestore_banner_slider widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Slider Block', 'sparklestore' );
	}

	/**
	 * Retrieve Sparklestore_banner_slider widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-slider-album';
	}

	/**
	 * Retrieve the list of categories the Sparklestore_banner_slider widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories(){
		return array( 'sparklestore-widget-blocks' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		
        $this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'sparklestore' ),
				
			]
		);
        
            //Slider layout.
            $this->add_control(
                'slider_layout',
                [
                    'label'       => esc_html__( 'Slider Layout', 'sparklestore' ),
                    'type'        => Controls_Manager::SELECT,
                    'label_block' => true,
                    'default'     => 'sliderpromo',
                    'options' => 
                    [
                        'fullslider'  => esc_html__('Full Slider', 'sparklestore'),
                        'sliderpromo'  => esc_html__('Slider With Promo Images', 'sparklestore')
                    ],
                ]
            );
    
                $repeater = new \Elementor\Repeater();

                //Upload Slider Image
                $repeater->add_control(
                    'slider_image',
                    [
                        'label'     => esc_html__( 'Upload Slider Image :', 'sparklestore' ),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'label_block' => true,
                    ]
                );

                //Slider Title Text
                $repeater->add_control(
                    'slider_title',
                    [
                        'label'     => esc_html__( 'Enter Slider Title :', 'sparklestore' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'label_block' => true,
                        
                    ]
                );

                //Slider Title Text
                $repeater->add_control(
                    'slider_sub_title',
                    [
                        'label'     => esc_html__( 'Enter Slider Sub Title :', 'sparklestore' ),
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'label_block' => true,
                        
                    ]
                );

                //Slider Button Text
                $repeater->add_control(
                    'slider_button_text',
                    [
                        'label'     => esc_html__( 'Enter Button Text :', 'sparklestore' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'label_block' => true,
                        
                    ]
                );

                //Slider Button Link
                $repeater->add_control(
                    'slider_button_link',
                    [
                        'label'     => esc_html__( 'Button Link :', 'sparklestore' ),
                        'type' => \Elementor\Controls_Manager::URL,
                        'label_block' => true,
                        
                    ]
                );

                $this->add_control(
                    'list',
                    [
                        'label' => __( 'Slider Item', 'sparklestore' ),
                        'type' => \Elementor\Controls_Manager::REPEATER,
                        'fields' => $repeater->get_controls(),
                        'default' => [
                            [   
                                'slider_image'       => esc_html__( 'Upload Slider Image', 'sparklestore' ),
                                'slider_title'       => esc_html__( 'Slider Title', 'sparklestore' ),
                                'slider_sub_title'   => esc_html__( 'Slider Sub Title', 'sparklestore' ),
                                'slider_button_text' => esc_html__( 'Enter Button Text', 'sparklestore' ),
                                'slider_button_link' => esc_html__( 'Button Link', 'sparklestore' ),
                            ]
                        ]
                    ]
                );
    
                // Slider Promo One
                $this->add_control(
                    'slider_promo_image_one',
                    [
                        'label'     => esc_html__( 'Slider Promo Image One :', 'sparklestore' ),
                        'type'      => Controls_Manager::MEDIA,
                        'label_block' => true,
                    ]
                );

                //first link
                $this->add_control(
                    'slider_promo_one_link',
                    [
                        'label'     => esc_html__( 'Custom Promo Link :', 'sparklestore' ),
                        'type'      => Controls_Manager::URL,
                        'label_block' => true,
                    ]
                );
                
                // Slider Promo Two
                $this->add_control(
                    'slider_promo_image_two',
                    [
                        'label'     => esc_html__( 'Slider Promo Image Two :', 'sparklestore' ),
                        'type'      => Controls_Manager::MEDIA,
                        'label_block' => true,
                    ]
                );

                //first link
                $this->add_control(
                    'slider_promo_two_link',
                    [
                        'label'     => esc_html__( 'Custom Promo Link :', 'sparklestore' ),
                        'type'      => Controls_Manager::URL,
                        'label_block' => true,
                        
                    ]
                );

		    $this->end_controls_section();
		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		    $settings       = $this->get_settings_for_display();
            $slider_layout = $settings['slider_layout'];
            $banner_slider = $settings['list'];
            $promoimage_one     = $settings['slider_promo_image_one']['url'];
            $promoimage_one_url = $settings['slider_promo_one_link'];
            $promoimage_two     = $settings['slider_promo_image_two']['url'];
            $promoimage_two_url = $settings['slider_promo_two_link'];
        ?>
        <div class="<?php if( !empty( $slider_layout ) && $slider_layout == 'fullslider' ){ echo esc_attr( 'fullwidthslider' ); }else{ echo esc_attr( 'container' );  } ?>">
            <div class="slider-inner-wrap <?php echo esc_attr( $slider_layout ); ?>">
                <?php if( !empty( $slider_layout ) && $slider_layout == 'sliderpromo' ){ ?>
                    <div class="sparklestore_banner_promo_wrap">
                        <div id="home" class="home-section banner-height">
                            <div class="sparklestore-slider">
                                <ul class="slides">
                                    <?php
                                        foreach($banner_slider as $slider){ 
                                    ?>
                                        <li class="bg-dark" style="background-image: url('<?php echo esc_url( $slider['slider_image']['url'] ); ?>');">
                                            <div class="home-slider-overlay"></div>
                                            <div class="sparklestore-caption text-center">
                                                <div class="caption-content">
                                                    <h2><?php echo esc_html( $slider['slider_title'] ); ?></h2>
                                                    <p><?php echo esc_html( $slider['slider_sub_title'] ); ?></p>
                                                    <?php if($slider['slider_button_text']): ?>
                                                        <div class="sliderbtn-wrp">
                                                            <a class="btn btn-primary" href="<?php echo esc_url($slider['slider_button_link']['url']); ?>">
                                                                <?php echo esc_html($slider['slider_button_text']); ?>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>                          
                                            </div>
                                        </li>
                                    <?php } ?>                    
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="sparklestore_wrap_promo">
                        <div class="sparklestore_promo_wrap">
                            <a class="sparklestore_single_promo" href="<?php echo esc_url( $promoimage_one_url ); ?>" target="_blank">
                                <img src="<?php echo esc_url( $promoimage_one ); ?>">
                            </a>
                        </div>

                        <div class="sparklestore_promo_wrap">
                            <a class="sparklestore_single_promo second_single_promo" href="<?php echo esc_url( $promoimage_two_url ); ?>" target="_blank">
                                <img src="<?php echo esc_url( $promoimage_two ); ?>">
                            </a>
                        </div>
                    </div>
                <?php }else{ ?>
                    <div id="home" class="home-section banner-height">
                        <div class="sparklestore-slider">
                            <ul class="slides">
                                <?php
                                    foreach($banner_slider as $slider){ 
                                ?>
                                    <li class="bg-dark" style="background-image: url('<?php echo esc_url( $slider['slider_image']['url'] ); ?>');">
                                        <div class="home-slider-overlay"></div>
                                        <div class="sparklestore-caption text-center">
                                            <div class="caption-content">
                                                <h2><?php echo esc_html( $slider['slider_title'] ); ?></h2>
                                                <p><?php echo esc_html( $slider['slider_sub_title'] ); ?></p>
                                                <?php if($slider['slider_button_text']): ?>
                                                    <div class="sliderbtn-wrp">
                                                        <a class="btn btn-primary" href="<?php echo esc_url($slider['slider_button_link']['url']); ?>">
                                                            <?php echo esc_html($slider['slider_button_text']); ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>                          
                                        </div>
                                    </li>
                                <?php } ?>                    
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

		<?php
	}
}