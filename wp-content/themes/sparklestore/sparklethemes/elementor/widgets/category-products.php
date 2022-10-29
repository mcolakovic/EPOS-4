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

class Sparklestore_Category_Products extends Widget_Base{

	/**
	 * Retrieve Sparklestore_Category_Products widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sparklestore-category-products';
	}

	/**
	 * Retrieve Sparklestore_Category_Products widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Category With Product', 'sparklestore' );
	}

	/**
	 * Retrieve Sparklestore_Category_Products widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Retrieve the list of categories the Sparklestore_Category_Products widget belongs to.
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
			'block_title_layout',
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
			'sparklestore_cat_product_title',
			[
				'label'     => esc_html__( 'Enter Section Title :', 'sparklestore' ),
				'type'      => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		//section subtitle.
		$this->add_control(
			'sparklestore_cat_product_short_desc',
			[
				'label'     => esc_html__( 'Enter Section Subitle :', 'sparklestore' ),
				'type'      => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);

		//image alighment
		$this->add_control(
			'sparklestore_cat_image_aligment',
			[
				'label'       => esc_html__( 'Image Alignment Style ', 'sparklestore' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'leftalign',
				'options' => 
				[
					'rightalign' => esc_html__('Right Align Category Image', 'sparklestore'),
            		'leftalign' => esc_html__('Left Align Category Image', 'sparklestore'),
				],
			]
		);

		//Single category.
		$this->add_control(
			'sparklestore_woo_category',
			[
				'label' => __( 'Select Category :', 'sparklestore' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => false,
				'options' => sparkle_woocommerce_category(),
			]
		);

		//section number product.
		$this->add_control(
			'sparklestore_cat_product_number',
			[
				'label'     => esc_html__( 'No of products :', 'sparklestore' ),
				'type'      => Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => '10',
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
        $title_layout     = empty( $settings['block_title_layout'] ) ? 'layout_one' : $settings['block_title_layout']; 
        $title            = empty( $settings['sparklestore_cat_product_title'] ) ? '' : $settings['sparklestore_cat_product_title']; 
        $shot_desc        = empty( $settings['sparklestore_cat_product_short_desc'] ) ? '' : $settings['sparklestore_cat_product_short_desc'];
        $cat_aligment     = empty( $settings['sparklestore_cat_image_aligment'] ) ? 'rightalign' : $settings['sparklestore_cat_image_aligment'];
        $product_category = empty( $settings['sparklestore_woo_category'] ) ? '' : $settings['sparklestore_woo_category'];
        $product_number   = empty( $settings['sparklestore_cat_product_number'] ) ? 5 : $settings['sparklestore_cat_product_number'];
        $cat_info         = empty( $settings['sparklestore_cat_cat_product_info'] ) ? 'yes' : $settings['sparklestore_cat_cat_product_info'];
		if( !empty( $product_category ) ){
		  $cat_id = get_term($product_category,'product_cat');
		  
          $category_id = $cat_id->term_id;
          $category_link = get_term_link( $category_id,'product_cat' );
        }
        else{
          $category_link = get_permalink( wc_get_page_id( 'shop' ) );
        } 
    ?>  

        <div class="categorproducts <?php echo esc_attr( $title_layout ); ?>">
            <div class="container">                
            	<div class="categoryarea-wrap">                
                	<div id="categoryproductslider" class="categoryproductslider <?php echo esc_attr( $cat_aligment ); ?>">
						<?php echo esc_html( $title ); ?>
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
						
						<div class="categoryproductwrap">
							<div class="homeblockinner"> 
								<?php 
									$taxonomy = 'product_cat';                                
									$terms = term_description( $product_category, $taxonomy );
									$terms_name = get_term( $product_category, $taxonomy );
									$thumbnail_id = get_term_meta( $product_category, 'thumbnail_id', true);
									if ( !empty( $thumbnail_id ) ) {
										$image = wp_get_attachment_image_src($thumbnail_id, 'shop_single');
									} else{ 
										$no_image = 'https://via.placeholder.com/285x370';
									}  
								?>
								<div class="catblockwrap" <?php if (!empty($thumbnail_id)) {  ?>style="background-image:url(<?php echo esc_url($image[0]); ?>);"<?php } else{ ?>style="background-image:url(<?php echo esc_url($no_image); ?>);"<?php } ?>>
									<a href="<?php echo esc_url($category_link); ?>" class="sparkle-overlay"></a>
									<div class="block-title-desc">                                
										<div class="table-outer">
											<div class="table-inner">
												<?php if( !( $terms_name->name ) ) { ?><h2><a href="<?php echo esc_url( $category_link ); ?>"><?php echo esc_html( $terms_name->name ); ?></a></h2><?php } ?>
												<?php echo esc_attr( $terms ); ?>
												<a href="<?php echo esc_url($category_link); ?>" class="btn btn-primary"><?php esc_html_e('Shop Now','sparklestore'); ?></a>
											</div>
										</div>                        
									</div>                        
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