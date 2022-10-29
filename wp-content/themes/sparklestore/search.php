<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Sparkle_Store
 */

get_header(); ?>

<?php do_action( 'sparklestore-breadcrumbs' ); ?>

<div class="container">
	<div class="site-wrapper">
		
	  	<div id="primary" class="content-area">
			<main id="main" class="site-main articlesListing blog-grid" role="main">
				<?php
					if ( have_posts() ) :

						/* Start the Loop */
						while ( have_posts() ) : the_post();

							/*
							 * Include the Post-Type-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
							*/
							get_template_part( 'template-parts/content', get_post_format() );

						endwhile;

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