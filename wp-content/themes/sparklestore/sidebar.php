<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package SparkleStore
 */

if( is_home() ){
		
	$post_sidebar = get_theme_mod( 'sparklestore_home_page_blog_sidebar','rightsidebar' );

}elseif( is_category() || is_tag() || is_attachment() || is_author() || is_archive() ){

	$post_sidebar = get_theme_mod( 'sparklestore_archive_sidebar','rightsidebar' );

}elseif( is_search() ){

	$post_sidebar = get_theme_mod( 'sparklestore_search_sidebar','rightsidebar' );

}else{

	$post_sidebar = get_post_meta( $post->ID, 'sparklestore_page_layouts', true );

	if(!$post_sidebar){

		$post_sidebar = 'rightsidebar';
	}
}

if ( $post_sidebar ==  'nosidebar' ) {
	return;
}

if( $post_sidebar == 'rightsidebar' || $post_sidebar == 'bothsidebar'  && is_active_sidebar('sparklesidebarone')){ ?>
		
		<aside id="secondary" class="widget-area right" role="complementary">

			<?php dynamic_sidebar( 'sparklesidebarone' ); ?>

		</aside><!-- #secondary -->
	<?php
}

if( $post_sidebar == 'leftsidebar' || $post_sidebar == 'bothsidebar'  && is_active_sidebar('sparklesidebartwo')){ ?>

		<aside id="secondary" class="widget-area left" role="complementary">

			<?php dynamic_sidebar( 'sparklesidebartwo' ); ?>

		</aside><!-- #secondary -->
	<?php
}