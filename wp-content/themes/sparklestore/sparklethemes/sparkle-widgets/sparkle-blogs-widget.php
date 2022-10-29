<?php
/**
 ** Adds sparklestore_blog_widget widget.
*/
add_action('widgets_init', 'sparklestore_blog_widget');
function sparklestore_blog_widget() {
    register_widget('sparklestore_blog_widget_area');
}

class sparklestore_blog_widget_area extends WP_Widget {

    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'sparklestore_blog_widget_area', esc_html__('&bull; SP : Blogs Widget','sparklestore'), array(
            'description' => esc_html__('A widget that shows blogs posts', 'sparklestore')
        ));
    }
    
    private function widget_fields() {
        
        $args = array(
          'type'       => 'post',
          'child_of'   => 0,
          'orderby'    => 'name',
          'order'      => 'ASC',
          'hide_empty' => 1,
          'taxonomy'   => 'category',
        );
        $categories = get_categories( $args );
        $cat_lists = array();
        foreach( $categories as $category ) {
            $cat_lists[$category->term_id] = $category->name;
        }

        $title_style = array(
            'layout_one'  => esc_html__('Layout One', 'sparklestore'),
            'layout_two'  => esc_html__('Layout Two', 'sparklestore')
        );

        $fields = array( 
            
            'block_title_layout' => array(
                'sparklestore_widgets_name'         => 'block_title_layout',
                'sparklestore_widgets_title'        => esc_html__( 'Select Block Title Style', 'sparklestore' ),
                'sparklestore_widgets_default'      => 'layout_one',
                'sparklestore_widgets_field_type'   => 'select',
                'sparklestore_widgets_field_options' => $title_style
            ),

            'sparklestore_blogs_top_title' => array(
                'sparklestore_widgets_name' => 'sparklestore_blogs_top_title',
                'sparklestore_widgets_title' => esc_html__('Blogs Main Title', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'title',
            ),

            'sparklestore_blogs_short_desc' => array(
                'sparklestore_widgets_name' => 'sparklestore_blogs_short_desc',
                'sparklestore_widgets_title' => esc_html__('Blogs Very Short Description', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'textarea',
                'sparklestore_widgets_row'    => 4,
            ),

            'blogs_category_list' => array(
              'sparklestore_widgets_name' => 'blogs_category_list',
              'sparklestore_mulicheckbox_title' => esc_html__('Select Blogs Category', 'sparklestore'),
              'sparklestore_widgets_field_type' => 'multicheckboxes',
              'sparklestore_widgets_field_options' => $cat_lists
            ),

            'sparklestore_number_blogs_posts' => array(
                'sparklestore_widgets_name' => 'sparklestore_number_blogs_posts',
                'sparklestore_widgets_title' => esc_html__('Enter Display Numebr of Posts', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'number',
                'sparklestore_widgets_default'    => 6,
            ),

            'block_display_layout' => array(
                'sparklestore_widgets_name'           => 'block_display_layout',
                'sparklestore_widgets_title'        => esc_html__( 'Display Block Layouts', 'sparklestore' ),
                'sparklestore_widgets_default'      => 'layout1',
                'sparklestore_widgets_field_type'   => 'selector',
                'sparklestore_widgets_field_options' => array(
                    'layout1' => esc_url( get_template_directory_uri() . '/assets/images/layout1.png' ),
                    'layout2' => esc_url( get_template_directory_uri() . '/assets/images/layout2.png' )
                )
            ),
            
        );

        return $fields;
    }

    public function widget($args, $instance) {        
        extract($args);
        extract($instance);
        /**
         * wp query for first block
        */
        $title_layout      = empty( $instance['block_title_layout'] ) ? 'layout_one' : $instance['block_title_layout'];
        $blog_main_title   = empty( $instance['sparklestore_blogs_top_title'] ) ? '' : $instance['sparklestore_blogs_top_title'];
        $shot_desc         = empty( $instance['sparklestore_blogs_short_desc'] ) ? '' : $instance['sparklestore_blogs_short_desc'];
        $block_cat_id      = empty( $instance['blogs_category_list'] ) ? '' : $instance['blogs_category_list'];
        $post_number       = empty( $instance['sparklestore_number_blogs_posts'] ) ? 6 : $instance['sparklestore_number_blogs_posts'];
        $block_layout      = empty( $instance['block_display_layout'] ) ? 'layout1' : $instance['block_display_layout'];

        if( !empty( $block_cat_id ) ) {
            $checked_cats = array();
            foreach( $block_cat_id as $cat_key => $cat_value ){            
                $checked_cats[] = $cat_key;
            }
        } else {
            return;
        }

        $post_category = implode( ",", $checked_cats );
        $post_category = explode( ',', $post_category );

        $product_args = array(
            'post_type' => 'Post',
            'tax_query' => array(
                array(
                    'taxonomy'  => 'category',
                    'field'     => 'term_id', 
                    'terms'     => $post_category                                                                 
                )
            ),
            'posts_per_page' => $post_number
        );

        echo $before_widget; 
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
                            $query = new WP_Query( $product_args );

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