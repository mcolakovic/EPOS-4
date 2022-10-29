<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package SparkleStore
 */

if( is_singular() ){

	$post_sidebar =  esc_attr( get_theme_mod( 'sparklestore_woocommerce_single_products_page_layout','rightsidebar' ) );

}else{

	$post_sidebar =  esc_attr( get_theme_mod( 'sparklestore_woocommerce_products_page_layout','rightsidebar' ) );
}

if( $post_sidebar == 'rightsidebar' && is_active_sidebar('sparklesidebarone')){ ?>

		<aside id="secondary" class="widget-area right" role="complementary">

			<?php dynamic_sidebar( 'sparklesidebarone' ); ?>

		</aside><!-- #secondary -->
	<?php
}

if( $post_sidebar == 'leftsidebar' && is_active_sidebar('sparklesidebartwo')){ ?>

		<aside id="secondary" class="widget-area left" role="complementary">

			<?php dynamic_sidebar( 'sparklesidebartwo' ); ?>

		</aside><!-- #secondary -->
	<?php
}

