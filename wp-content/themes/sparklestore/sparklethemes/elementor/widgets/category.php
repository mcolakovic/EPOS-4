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

class Sparklestore_Category extends Widget_Base{

	/**
	 * Retrieve Sparklestore_Category widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sparklestore-category';
	}

	/**
	 * Retrieve Sparklestore_Category widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Category Collection', 'sparklestore' );
	}

	/**
	 * Retrieve Sparklestore_Category widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-gallery-justified';
	}

	/**
	 * Retrieve the list of categories the Sparklestore_Category widget belongs to.
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

		//Blog section title.
		$this->add_control(
			'blog_title',
			[
				'label'     => esc_html__( 'Enter Section Title :', 'sparklestore' ),
				'type'      => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		//Blog section subtitle.
		$this->add_control(
			'blog_subtitle',
			[
				'label'     => esc_html__( 'Enter Section Subitle :', 'sparklestore' ),
				'type'      => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);

		//blog category.
		$this->add_control(
			'blog_category',
			[
				'label' => __( 'Select Multiple Category :', 'sparklestore' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => sparkle_woocommerce_category(),
			]
		);

		//Display Style
		$this->add_control(
			'block_display_style',
			[
				'label'       => esc_html__( 'Select Display Style :', 'sparklestore' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'category-style-1',
				'options' => 
				[
					'category-style-1'  => esc_html__('Display Style One', 'sparklestore'),
            		'category-style-2'  => esc_html__('Display Style Two', 'sparklestore')
				],
			]
		);

		//Display Block Layouts
		$this->add_control(
			'block_display_layout',
			[
				'label'       => esc_html__( 'Display Block Layouts', 'sparklestore' ),
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

		//section title style.
		// $this->start_controls_section(
		// 	'section_style',
		// 	[
		// 		'label' => __( 'Style', 'sparklestore' ),
		// 		'tab' => Controls_Manager::TAB_STYLE,
		// 	]
		// );

		// $this->add_control(
		// 	'columns',
		// 	[
		// 		'label'       => esc_html__( 'Columns', 'sparklestore' ),
		// 		'type'        => Controls_Manager::SELECT,
		// 		'label_block' => true,
		// 		'default'     => 3,

		// 		'options' => 
		// 		[
		// 			'1'    	=> esc_html__( 'Single Column', 'sparklestore' ),
		// 			'2'    	=> esc_html__( 'Two Column', 'sparklestore' ),
		// 			'3'  	=> esc_html__( 'Three Column', 'sparklestore' ),
		// 			'4'   	=> esc_html__( 'Four Column', 'sparklestore' ),
		// 		],
		// 	]
		// );
		//$this->end_controls_section();
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
		
		$main_title     	= $settings['blog_title'];
		$shot_desc  		= $settings['blog_subtitle'];
		$sparklestore_cat_id  = $settings['blog_category'];
		$blog_columns    	= isset($settings['columns']) ? $settings['columns'] : 3;
		$title_layout    	= $settings['title_layout'];
		$block_layout    	= $settings['block_display_layout'];
		$display_style      = $settings['block_display_style'];
		
		// $blog_columns = 12 / intval($blog_columns);
		
    ?>
        <div class="categoryarea <?php echo esc_attr( $title_layout ); ?>">           
            <div class="container">               
				<div class="categoryarea-wrap <?php echo esc_attr( $block_layout ); ?>"> 
					<?php if( !empty( $main_title ) ) { ?>
						<div class="blocktitlewrap <?php echo esc_attr( $block_layout ); ?>">
							<div class="blocktitle">
								<?php if( !empty( $main_title ) ) { ?><h2><?php echo esc_html( $main_title ); ?></h2> <?php } ?>
								
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
					<ul class="<?php echo esc_attr( $display_style ); ?> <?php if( !empty( $block_layout ) && $block_layout == 'layout1' ){ echo esc_attr( 'categoryslider cS-hidden' ); }else{ echo esc_attr( 'storeproductlist' ); }  ?>">
                        <?php
                            $count = 0; 
                            if(!empty( $sparklestore_cat_id ) ){
                                
                                foreach ($sparklestore_cat_id as $key) {          
                                    $thumbnail_id = get_term_meta( $key, 'thumbnail_id', true );
                                    $images = wp_get_attachment_url( $thumbnail_id );
                                    $image = wp_get_attachment_image_src($thumbnail_id, 'sparklestore-cat-collection-image', true);
                                    $term = get_term_by( 'id', $key, 'product_cat');
                                if ( $term && ! is_wp_error( $term ) ) {
                                    $term_link = get_term_link($term);
                                    $term_name = $term->name;
                                    $sub_count =  apply_filters( 'woocommerce_subcategory_count_html', ' ' . $term->count . ' '.esc_html__('Products','sparklestore').'', $term);
                                }else{
                                    $term_link = '#';
                                    $term_name = esc_html__('Category','sparklestore');
                                    $sub_count = '0 '.esc_html__('Products','sparklestore');
                                }
                                
                            $no_img = 'https://via.placeholder.com/285x370';
						?>
							<li class="product-category product">
                                <div class="product-wrapper">
                                    <a href="<?php echo esc_url($term_link); ?>">
                                        <div class="products-cat-wrap">
                                            <div class="products-cat-image">
                                                <?php  
                                                    if ( $images ) {
                                                      echo '<img class="categoryimage" src="' . esc_url( $image[0] ) . '" />';
                                                    } else{
                                                      echo '<img class="categoryimage" src="' . esc_url( $no_img ) . '" />';
                                                    }
                                                ?>
                                            </div>
                                            <div class="products-cat-info">
                                                <h3 class="woocommerce-loop-category__title">
                                                    <?php echo esc_attr($term_name); ?>
                                                    <span class="count"><?php echo esc_html( $sub_count );  ?></span>
                                                </h3>
                                            </div>
                                        </div>
                                    </a>            
                                </div>         
							</li>
                        <?php } }  ?>
                    </ul>
                </div>
            </div>
        </div>
		
		
		<?php
	}



}