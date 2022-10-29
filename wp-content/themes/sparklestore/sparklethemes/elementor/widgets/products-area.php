<?php
/**
 * StparkleStore Products.
 *
 * @package    SparkleThemes
 * @subpackage SparkleStore
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	return; // Exit if it is accessed directly
}

class Sparklestore_WooProdcuts extends Widget_Base{

	/**
	 * Retrieve Sparklestore_WooProdcuts widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sparklestore-woo-prodcuts';
	}

	/**
	 * Retrieve Sparklestore_WooProdcuts widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Product Area', 'sparklestore' );
	}

	/**
	 * Retrieve Sparklestore_WooProdcuts widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Retrieve the list of categories the Sparklestore_WooProdcuts widget belongs to.
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
        
        // Select Product Type :
        $this->add_control(
			'product_type',
			[
				'label' => __( 'Select Product Type :', 'sparklestore' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options' => array(
					''                => esc_html__('Select Product Type', 'sparklestore'),
					'latest_product'  => esc_html__('Latest Product', 'sparklestore'),
					'upsell_product'  => esc_html__('UpSell Product', 'sparklestore'),
					'feature_product' => esc_html__('Feature Product', 'sparklestore'),
					'on_sale'         => esc_html__('On Sale Product', 'sparklestore')
				),
				'default' => 'latest_product'
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
        $product_type     = $settings['product_type'];
        $product_number   = $settings['no_of_products']['size'];
        $block_layout     = $settings['block_display_layout'];

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
                'posts_per_page'    => 8,
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
							$query = new \WP_Query( $product_args );
							if($query->have_posts()) {  while($query->have_posts()) { $query->the_post();
						?>
							<?php wc_get_template_part( 'content', 'product' ); ?>
							
						<?php } } wp_reset_postdata(); unset($GLOBALS['product_label_custom']); ?>                    
					</ul>
				</div>        
            </div>        
        </div><!-- End Product Slider -->

		<?php
	}



}