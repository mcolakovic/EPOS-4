<?php
/**
 * The template for displaying archived woocommerce products
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @package Bosa
 */
get_header(); 
?>
<div id="content" class="site-content">
	<div class="container">
		<section class="wrap-detail-page ">
				<h1 class="page-title">
					<?php if( !bosa_wooCom_is_product_page() || !get_theme_mod( 'disable_single_product_title', false ) ){
						woocommerce_page_title();
					} ?>
				</h1>
				<?php
				if( !bosa_wooCom_is_product_page() ){
					if( get_theme_mod( 'breadcrumbs_controls', 'show_in_all_page_post' ) == 'disable_in_all_pages' || get_theme_mod( 'breadcrumbs_controls', 'show_in_all_page_post' ) == 'show_in_all_page_post' ){
						bosa_breadcrumb_wrap();
					}
				} ?>
				<div class="row">
					<?php
					$getSidebarClass = bosa_get_sidebar_class();
					$sidebarClass = 'col-12';
					if( !bosa_wooCom_is_product_page() ){
						$sidebarClass = $getSidebarClass[ 'sidebarClass' ];
						bosa_woo_product_detail_left_sidebar( $getSidebarClass[ 'sidebarColumnClass' ] );
					}	
					?>
					
					<div id="primary" class="content-area <?php echo esc_attr( $sidebarClass ); ?>">
						<main id="main" class="site-main post-detail-content woocommerce-products" role="main">
							<?php if ( have_posts() ) :
								woocommerce_content();
							endif;
							?>
						</main><!-- #main -->
					</div><!-- #primary -->
					<?php
					if( !bosa_wooCom_is_product_page() ){
						bosa_woo_product_detail_right_sidebar( $getSidebarClass[ 'sidebarColumnClass' ] );
					} ?>
				</div>
		</section>
	</div><!-- #container -->
</div><!-- #content -->
<?php
get_footer();
