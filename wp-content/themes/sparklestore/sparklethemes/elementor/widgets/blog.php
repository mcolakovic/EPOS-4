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

class Sparklestore_Blog extends Widget_Base{

	/**
	 * Retrieve Sparklestore_Blog widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sparklestore-blog';
	}

	/**
	 * Retrieve Sparklestore_Blog widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Blog', 'sparklestore' );
	}

	/**
	 * Retrieve Sparklestore_Blog widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-masonry';
	}

	/**
	 * Retrieve the list of categories the Sparklestore_Blog widget belongs to.
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
			'blog_title',
			[
				'label'     => esc_html__( 'Enter Section Title :', 'sparklestore' ),
				'type'      => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		//section subtitle.
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
				'options' => sparklestore_categories(),
			]
		);

		$this->add_control(
			'no_of_post',
			[
				'label' => __( 'No of post', 'sparklestore' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				'default' => [
					'size' => 6,
				]
			]
		);

		//Display layout.
		$this->add_control(
			'block_display_layout',
			[
				'label'       => esc_html__( 'Blog Layouts', 'sparklestore' ),
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

		$blog_main_title = $settings['blog_title'];
		$shot_desc  	 = $settings['blog_subtitle'];
		$blog_category   = $settings['blog_category'];
		$title_layout    = $settings['title_layout'];
		$block_layout    = $settings['block_display_layout'];
		$no_of_post    	 = $settings['no_of_post']['size'];

		//$blog_columns    = $settings['columns'];
		//$blog_columns = 12 / intval($blog_columns);


		$post_category = implode( ",", $blog_category );
        $post_category = explode( ',', $post_category );

        $blogs_args = array(
            'post_type' => 'post',
            'tax_query' => array(
                array(
                    'taxonomy'  => 'category',
                    'field'     => 'term_id', 
                    'terms'     => $post_category                                                                 
                )
            ),
            'posts_per_page' => $no_of_post
		);
		
		?>
		<div class="sparklestore-blogwrap <?php echo esc_attr( $title_layout ); ?>">
            <div class="container">
				<div class="categoryarea-wrap <?php echo esc_attr( $block_layout ); ?>">
					<?php if( !empty( $blog_main_title ) ) { ?>
						<div class="blocktitlewrap <?php echo esc_attr( $block_layout ); ?>">
							<div class="blocktitle">
								<?php if( !empty( $blog_main_title ) ) { ?>
										<h2><?php echo esc_html( $blog_main_title ); ?></h2>
								<?php if(!empty( $shot_desc )) { ?>
									<p><?php echo esc_html( $shot_desc ); ?></p>
								<?php } } ?>
								<?php if( !empty( $block_layout ) && $block_layout == 'layout1' ){ ?>
									<div class="SparkleStoreAction">
										<div class="sparkle-lSPrev"></div>
										<div class="sparkle-lSNext"></div>
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<ul class="sparklestore_blog_wrap <?php if( !empty( $block_layout ) && $block_layout == 'layout1' ){ echo esc_attr( 'blogspostarea cS-hidden' ); }else{ echo esc_attr( 'storeproductlist category-style-2' ); }  ?>">
                        <?php 
                            $query = new \WP_Query( $blogs_args );

							if($query->have_posts()) {  while($query->have_posts()) { $query->the_post();
								
								$postformat = get_post_format();
								$blogreadmore_btn = get_theme_mod( 'sparklestore_blogtemplate_btn', 'Read More' );

                        ?>
                            <li class="product articlesListing blog-grid">
								<article id="post-<?php the_ID(); ?>" <?php post_class('article text-center'); ?> itemtype="http://schema.org/BlogPosting" itemtype="http://schema.org/BlogPosting">
								
									<?php sparklestore_post_format_media( $postformat ); ?>
								
									<div class="box">
										<?php 
											the_title( '<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); 
								
											if ( 'post' === get_post_type() ){ do_action( 'sparklestore_post_meta', 10 ); } 
										?>
										<div class="entry-content">
											<p>
                                                <?php echo esc_html( wp_trim_words( get_the_content(), 15, '...' ) ); ?>
                                            </p>
										</div>
										<div class="btn-wrap">
											<a class="btn btn-primary" href="<?php the_permalink(); ?>">
												<span><?php echo esc_html( $blogreadmore_btn ); ?> <i class="icofont-double-right"></i></span>
											</a>
										</div>
									</div>
								</article><!-- #post-<?php the_ID(); ?> -->
                            </li>
                            
                        <?php } } wp_reset_postdata(); ?>                    
					</ul>
                </div>

            </div>

        </div><!-- End Latest Blog -->
		
		<?php
	}



}