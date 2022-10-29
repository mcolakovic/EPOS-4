<?php
/**
 * StparkleStore Tabs Products.
 *
 * @package    SparkleThemes
 * @subpackage SparkleStore
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	return; // Exit if it is accessed directly
}

class Sparklestore_WooTabProdcuts extends Widget_Base{

	/**
	 * Retrieve Sparklestore_WooTabProdcuts widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sparklestore-woo-tab-products';
	}

	/**
	 * Retrieve Sparklestore_WooTabProdcuts widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Category Tabs', 'sparklestore' );
	}

	/**
	 * Retrieve Sparklestore_WooTabProdcuts widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-tabs';
	}

	/**
	 * Retrieve the list of categories the Sparklestore_WooTabProdcuts widget belongs to.
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

		//title layout.
		$this->add_control(
			'title_layout',
			[
				'label'       => esc_html__( 'Title Style', 'sparklestore' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'layout_one',
				'options' => 
				[
					'layout_one'  => esc_html__('Layout One', 'sparklestore'),
            		'layout_two'  => esc_html__('Layout Two', 'sparklestore')
				],
			]
		);

		//section title.
		$this->add_control(
			'title',
			[
				'label'     => esc_html__( 'Enter Section Title :', 'sparklestore' ),
				'type'      => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		//section subtitle.
		$this->add_control(
			'subtitle',
			[
				'label'     => esc_html__( 'Enter Section Subitle :', 'sparklestore' ),
				'type'      => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
        );
        
        //woo category.
		$this->add_control(
			'category',
			[
				'label' => __( 'Select Category :', 'sparklestore' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => sparkle_woocommerce_category(),
			]
		);

		$this->add_control(
			'no_of_products',
			[
				'label' => __( 'No of Products', 'sparklestore' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				'default' => [
					'size' => 8,
				]
			]
		);

		//Display layout.
		$this->add_control(
			'block_display_layout',
			[
				'label'       => esc_html__( 'Block Layouts', 'sparklestore' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'layout1',
				'options' => 
				[
					'layout1' => esc_html( "Slider Layout", 'sparklestore' ),
                    'layout2' => esc_html( "Grid Layout", 'sparklestore' )
				],
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
		/**
         * wp query for first block
        */  
        $title_layout     = $settings['title_layout'];
        $title            = $settings['title'];
        $shot_desc        = $settings['subtitle'];
        $sparklestore_cat_id = $settings['category'];
        $product_number   = $settings['no_of_products']['size'];
        $block_layout     = $settings['block_display_layout'];

        if(!empty( $sparklestore_cat_id )) {
           $first_cat_id =  $sparklestore_cat_id[0];
        }

   ?>

	<div class="sparkletabsproductwrap <?php echo esc_attr( $title_layout ); ?>">           
		<div class="container"> 
			<div class="categoryarea-wrap <?php echo esc_attr( $block_layout ); ?>">

				<div class="sparkletabs blocktitlewrap <?php echo esc_attr( $block_layout ); ?>">
                    
					<?php if( !empty( $title_layout ) && $title_layout =='layout_one' ){ if(!empty( $title )) { ?>
						<div class="blocktitle">
							<h2><?php echo esc_html( $title ); ?></h2>
							<?php if(!empty( $shot_desc )) { ?><p><?php echo esc_html( $shot_desc ); ?></p><?php } ?>
						</div>
					<?php } } ?>

					<ul class="sparkletablinks sparkletablinks_ele" data-id="<?php echo intval( $product_number ); ?>" data-layout="<?php echo esc_attr( $block_layout ); ?>">
						<?php
							if(!empty($sparklestore_cat_id)){
								foreach ($sparklestore_cat_id as $key) {
									$term = get_term_by( 'id', $key, 'product_cat');
									if( !$term ) continue;
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
							<ul class="<?php if( !empty( $block_layout ) && $block_layout == 'layout1' ){ echo esc_attr( 'tabsproduct_ele cS-hidden' ); }else{ echo esc_attr( 'storeproductlist' ); }  ?>">                            
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
									$query = new \WP_Query($product_args);

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
        
	}



}