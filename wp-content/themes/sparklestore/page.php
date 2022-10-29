<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sparkle_Store
 */

get_header(); ?>

<?php do_action( 'sparklestore-breadcrumbs' ); ?>

<div class="container">
    <div class="site-wrapper">
        <div id="primary" class="content-area">
            <main id="main" class="site-main articlesListing blog-grid">
                <?php

                    /* Start the Loop */
                    while ( have_posts() ) : the_post();

                        get_template_part( 'template-parts/content', 'page' );

                    endwhile;

                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :

                        comments_template();

                    endif;
                ?>
            </main><!-- #main -->
        </div><!-- #primary -->

        <?php get_sidebar(); ?>

    </div>
</div>

<?php get_footer();