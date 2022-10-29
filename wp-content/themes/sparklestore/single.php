<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Sparkle_Store
 */
get_header(); ?>

<?php do_action('sparklestore-breadcrumbs'); ?>

<div class="container">
    <div class="site-wrapper">
        <div id="primary" class="content-area">
            <main id="main" class="site-main articlesListing blog-grid">
                <?php
                    if ( have_posts() ) :

                        /* Start the Loop */
                        while ( have_posts() ) : the_post();

                            /*
                             * Include the Post-Type-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                             */
                            get_template_part( 'template-parts/content', 'single' );

                        endwhile;

                            the_post_navigation();

                            // If comments are open or we have at least one comment, load up the comment template.
                            if (comments_open() || get_comments_number()) :
                                comments_template();
                            endif;

                    else :

                        get_template_part( 'template-parts/content', 'none' );

                    endif;
                ?>
            </main><!-- #main -->
        </div><!-- #primary -->

        <?php get_sidebar(); ?>

    </div>
</div>

<?php get_footer();
