<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package SparkleStore
 */

$layout = get_theme_mod( 'sparklestore_archive_layout', 'none' );

get_header(); ?>

<?php do_action( 'sparklestore-breadcrumbs' ); ?>

<div class="container">
	<div class="site-wrapper">
		
	  	<div id="primary" class="content-area <?php echo esc_attr( $layout  ); ?>" data-layout="<?php echo esc_attr( $layout  ); ?>">
			<main id="main" class="site-main articlesListing blog-grid" role="main">
				<?php
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
	        
	    <?php get_sidebar(); ?>

    </div>
</div>

<?php get_footer();