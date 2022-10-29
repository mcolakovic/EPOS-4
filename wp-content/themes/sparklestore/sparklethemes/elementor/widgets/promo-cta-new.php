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

class Sparklestore_Promo_Call_To_Action2 extends Widget_Base{

	/**
	 * Retrieve Sparklestore_Promo_Call_To_Action2 widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sparklestore-promo-column-cta';
	}

	/**
	 * Retrieve Sparklestore_Promo_Call_To_Action2 widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Grid Promo Widget', 'sparklestore' );
	}

	/**
	 * Retrieve Sparklestore_Promo_Call_To_Action2 widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-image-rollover';
	}

	/**
	 * Retrieve the list of categories the Sparklestore_Promo_Call_To_Action2 widget belongs to.
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
			'promo_1',
			[
				'label' => __( 'Promo Section One', 'sparklestore' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				
			]
		);

			$this->add_control(
				'promo_image_one',
				[
					'label'     => esc_html__( 'Upload Promo One Image :', 'sparklestore' ),
					'type'      => Controls_Manager::MEDIA,
					'label_block' => true,
				]
			);

			$this->add_control(
				'promo_block_one_title',
				[
					'label'     => esc_html__( 'Enter Promo Block One Title :', 'sparklestore' ),
					'type'      => Controls_Manager::TEXT,
					'label_block' => true,
				]
			);

			//first link
			$this->add_control(
				'promo_one_button_link',
				[
					'label'     => esc_html__( 'Promo One Link :', 'sparklestore' ),
					'type'      => Controls_Manager::URL,
					'label_block' => true,
					
				]
			);

		$this->end_controls_section();

		
		$this->start_controls_section(
			'promo_2',
			[
				'label' => __( 'Promo Section Two', 'sparklestore' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				
			]
		);

			$this->add_control(
				'promo_image_two',
				[
					'label'     => esc_html__( 'Upload Promo Two Image :', 'sparklestore' ),
					'type'      => Controls_Manager::MEDIA,
					'label_block' => true,
				]
			);

			$this->add_control(
				'promo_block_two_title',
				[
					'label'     => esc_html__( 'Enter Promo Block Two Title :', 'sparklestore' ),
					'type'      => Controls_Manager::TEXT,
					'label_block' => true,
				]
			);

			//first link
			$this->add_control(
				'promo_two_button_link',
				[
					'label'     => esc_html__( 'Promo Two Link :', 'sparklestore' ),
					'type'      => Controls_Manager::URL,
					'label_block' => true,
					
				]
			);

		$this->end_controls_section();

		
		$this->start_controls_section(
			'promo_3',
			[
				'label' => __( 'Promo Section Three', 'sparklestore' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				
			]
		);

			$this->add_control(
				'promo_image_three',
				[
					'label'     => esc_html__( 'Upload Promo Three Image :', 'sparklestore' ),
					'type'      => Controls_Manager::MEDIA,
					'label_block' => true,
				]
			);

			$this->add_control(
				'promo_block_three_title',
				[
					'label'     => esc_html__( 'Enter Promo Block Three Title :', 'sparklestore' ),
					'type'      => Controls_Manager::TEXT,
					'label_block' => true,
				]
			);

			//first link
			$this->add_control(
				'promo_three_button_link',
				[
					'label'     => esc_html__( 'Promo Three Link :', 'sparklestore' ),
					'type'      => Controls_Manager::URL,
					'label_block' => true,
					
				]
			);

		$this->end_controls_section();
		

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Settings', 'sparklestore' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				
			]
		);

		
		$this->add_control(
			'layout',
			[
				'label'     => esc_html__( 'Layout', 'sparklestore' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'options' => array(
					'onetothree' => esc_html__('1:3 (Layout)','sparklestore'),
					'twototwo'   => esc_html__('2:2 (Layout)','sparklestore'),
					'onetotwo'   => esc_html__('1:2 (Layout)','sparklestore'),
					'twotoone'   => esc_html__('2:1 (Layout)','sparklestore'),
				),
				'default' => 'onetothree'
			]
		);

		$this->add_control(
			'promo_info',
			[
				'label'     => esc_html__( 'Hide Promo Info', 'sparklestore' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'sparklestore' ),
				'label_off' => __( 'Hide', 'sparklestore' ),
				'return_value' => 1,
				'default' => 1,
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
		
		$promo_one_img     =  $settings['promo_image_one']['url'];
        $promo_one_title   =  $settings['promo_block_one_title'];
        $promo_one_url     =  $settings['promo_one_button_link']['url'];
        
        $promo_two_img     =  $settings['promo_image_two']['url'];
        $promo_two_title   =  $settings['promo_block_two_title'];
        $promo_two_url     =  $settings['promo_two_button_link']['url'];

        $promo_three_img   =  $settings['promo_image_three']['url'];
        $promo_three_title =  $settings['promo_block_three_title'];
        $promo_three_url   =  $settings['promo_three_button_link']['url'];
        
        $promo_layout      = $settings['layout'];
		$promo_info 	   = $settings['promo_info'];

        ?>

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
                                <?php if($promo_info == 1) { if( !empty( $promo_one_title ) ){ ?>
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
                                <?php if($promo_info == 1) { if( !empty( $promo_two_title ) ){ ?>
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
                                <?php if($promo_info == 1) { if( !empty( $promo_three_title ) ){ ?>
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

		<?php
	}
}