<?php
/**
 * The template for displaying  page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SparkleStore
 *
 * Template Name: Blog Template
 */

$post_sidebar = get_theme_mod( 'sparklestore_home_page_blog_sidebar','rightsidebar' );

$layout = get_theme_mod( 'sparklestore_home_page_blog_layout', 'none' );

$blog = get_theme_mod('sparklestore_blogtemplate_postcat');

$blog_cat_id = explode(',', $blog);

$args = array(
    'post_type' => 'post',
	'paged'     => get_query_var( 'paged' ),
	'tax_query' => array(
        array(
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => $blog_cat_id
        ),
    ),
);

get_header(); ?>

<?php do_action( 'sparklestore-breadcrumbs' ); ?>

<div class="container">
	<div class="site-wrapper">

		<?php
			if( $post_sidebar == 'leftsidebar' || $post_sidebar == 'bothsidebar'  && is_active_sidebar('sparklesidebartwo')){ ?>

				<aside id="secondary" class="widget-area left" role="complementary">

					<?php dynamic_sidebar( 'sparklesidebartwo' ); ?>

				</aside><!-- #secondary -->

				<?php 
			} 
		?>
		
	  	<div id="primary" class="content-area <?php echo esc_attr( $layout  ); ?>" data-layout="<?php echo esc_attr( $layout  ); ?>">
			<main id="main" class="site-main articlesListing blog-grid" role="main">
				<?php
					query_posts( $args );

					if ( have_posts() ) :


						if( !empty( $layout ) && $layout == 'masonry'){

							echo '<div class="sparklestore-masonry">';
						}

							/* Start the Loop */
							while ( have_posts() ) : the_post();

								/*
								 * Include the Post-Type-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
								*/
								get_template_part( 'template-parts/content', get_post_format() );

							endwhile;

						if( !empty( $layout ) && $layout == 'masonry'){

							echo '</div>';
						}

						the_posts_pagination( 
		            		array(
							    'prev_text' => esc_html__( 'Prev', 'sparklestore' ),
							    'next_text' => esc_html__( 'Next', 'sparklestore' ),
							)
			            );

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif;
				?>
	        </main>
	    </div>
	        
	    <?php
			if( $post_sidebar == 'rightsidebar' || $post_sidebar == 'bothsidebar'  && is_active_sidebar('sparklesidebarone')){ ?>
		
					<aside id="secondary" class="widget-area right" role="complementary">

						<?php dynamic_sidebar( 'sparklesidebarone' ); ?>

					</aside><!-- #secondary -->
				<?php
			}
		?>

    </div>
</div>

<?php get_footer();