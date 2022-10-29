<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Sparkle_Store
 */
get_header(); ?>

<?php do_action( 'sparklestore-breadcrumbs' ); ?>

<div class="container nosidebar">
    <div class="site-wrapper">
        <div id="primary" class="content-area-none">
            <main id="main" class="site-main articlesListing blog-grid">
                <section class="content-wrapper">
                    <div class="container">
                        <div class="std">
                            <div class="page-not-found">
                                <h2><?php esc_html_e('404','sparklestore'); ?></h2>
                                <h3><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/signal.png"><?php esc_html_e('Oops! The Page you requested was not found!', 'sparklestore'); ?></h3>
                                <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'sparklestore' ); ?></p>
                                <a class="btn btn-primary" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to home', 'sparklestore'); ?></a>
                            </div>
                        </div>
                    </div>
                </section>
            </main><!-- #main -->
        </div><!-- #primary -->
    </div>
</div>

<?php get_footer();
