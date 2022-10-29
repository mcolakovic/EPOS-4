<?php
/**
 * StparkleStore Blog.
 *
 * @package    SparkleThemes
 * @subpackage SparkleStore
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	return; // Exit if it is accessed directly
}

class Sparklestore_Promo_Call_To_Action extends Widget_Base{

	/**
	 * Retrieve Sparklestore_Promo_Call_To_Action widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sparklestore-promo-cta';
	}

	/**
	 * Retrieve Sparklestore_Promo_Call_To_Action widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Full Promo Widget (CTA)', 'sparklestore' );
	}

	/**
	 * Retrieve Sparklestore_Promo_Call_To_Action widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-image-rollover';
	}

	/**
	 * Retrieve the list of categories the Sparklestore_Promo_Call_To_Action widget belongs to.
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

		//Blog section title.
		$this->add_control(
			'page_id',
			[
				'label'     => esc_html__( 'Select Page', 'sparklestore' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple' => false,
				'label_block' => true,
				'options' => sparklestore_pages(),
			]
		);

		//first button text
		$this->add_control(
			'first_button_text',
			[
				'label'     => esc_html__( 'First Button Text:', 'sparklestore' ),
				'type'      => Controls_Manager::TEXT,
				'label_block' => true,
				
			]
		);

		//first button link
		$this->add_control(
			'first_button_link',
			[
				'label'     => esc_html__( 'First Button Link:', 'sparklestore' ),
				'type'      => Controls_Manager::URL,
				'label_block' => true,
				
			]
		);

		//first button link
		$this->add_control(
			'second_button_text',
			[
				'label'     => esc_html__( 'Second Button Text:', 'sparklestore' ),
				'type'      => Controls_Manager::TEXT,
				'label_block' => true,
				
			]
		);
		//second button link
		$this->add_control(
			'second_button_link',
			[
				'label'     => esc_html__( 'Second Button Link:', 'sparklestore' ),
				'type'      => Controls_Manager::URL,
				'label_block' => true,
				
			]
		);


		//second button link
		$this->add_control(
			'layout',
			[
				'label'     => esc_html__( 'Background Layout', 'sparklestore' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'options' => array(
					'fullbg'   => esc_html__('Full Width Background', 'sparklestore'),
					'boxedbg'   => esc_html__('Boxed Background', 'sparklestore')
				),
				'default' => 'boxedbg'
				
			]
		);

		//category info
		$this->add_control(
			'cat_product_info',
			[
				'label'     => esc_html__( 'Hide & Show Information', 'sparklestore' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'sparklestore' ),
				'label_off' => __( 'Hide', 'sparklestore' ),
				'return_value' => 'yes',
				'default' => 'yes',
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
		
		$block_promo_id   = $settings['page_id'];
        $button_link      = $settings['first_button_link'];
        $button_text      = $settings['first_button_text'];
        $button_one_link  = $settings['second_button_link'];
		$button_one_text  = $settings['second_button_text'];
		$block_promo_layout = $settings['layout'];
		$promo_info     = $settings['cat_product_info'];
        
		if( !empty( $block_promo_id ) ) {

		$block_promo_page = new \WP_Query( 'page_id='.$block_promo_id );

		if( $block_promo_page->have_posts() ) { while( $block_promo_page->have_posts() ) { $block_promo_page->the_post();
		
		$full_promo_image = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full', true );         

		if( !empty( $block_promo_layout ) && $block_promo_layout == 'fullbg' ){ ?>

			<div class="fullpromobanner">
				<div class="header-banner">
					<div class="banner-img">
						<img src="<?php echo esc_url( $full_promo_image[0] ); ?>">
					</div>
					<?php if($promo_info == 'yes') { ?>
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
						<?php if($promo_info == 'yes') { ?>
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

		}
}