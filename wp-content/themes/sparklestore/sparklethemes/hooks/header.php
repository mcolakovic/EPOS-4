<?php
/**
 * Header Section Skip Area
*/
if ( ! function_exists( 'sparklestore_skip_links' ) ) {
	/**
	 * Skip links
	 * @since  1.0.0
	 * @return void
	 */
	function sparklestore_skip_links() { ?>
		<a class="skip-link screen-reader-text" href="#site-content"><?php esc_html_e( 'Skip to content', 'sparklestore' ); ?></a>
		<?php
	}
}
add_action( 'sparklestore_header_before', 'sparklestore_skip_links', 5 );


if ( ! function_exists( 'sparklestore_header_before' ) ) {
	/**
	 * Header Area
	 * @since  1.0.0
	 * @return void
	*/
	function sparklestore_header_before() { ?>
		<header id="masthead" class="site-header" itemscope="itemscope" itemtype="http://schema.org/WPHeader" role="banner">		
			<div class="header-container">
		<?php
	}
}
add_action( 'sparklestore_header_before', 'sparklestore_header_before', 10 );

/**
 * Top Header Area
*/
if ( ! function_exists( 'sparklestore_top_header' ) ) {
	
	function sparklestore_top_header() { 

		$topheader = get_theme_mod( 'sparklestore_top_header_section_options','on' );

		if( !empty( $topheader ) && $topheader == 'on' ){ ?>
		
			<div class="header-top">
		        <div class="container">
		        	<div class="top-header-inner">
			            <div class="top-bar-menu left">
			            	<?php
								$topheaderleft = get_theme_mod( 'sparklestore_top_header_leftside_options', 'quickinfo' );
								
								if($topheaderleft =='topmenu'){

								    wp_nav_menu( array( 'theme_location' => 'sparkletopmenu', 'depth' => 1 ) );

								} else if($topheaderleft == 'quickinfo'){    

								    sparklestore_quick_contact();

								}else if($topheaderleft == 'socialicon'){    

								    sparklestore_social_links();

								}else{

									sparklestore_quick_contact();
								}
							?>
			            </div>

			            <div class="top-bar-menu right">
			            	<?php
								$topheaderright = get_theme_mod( 'sparklestore_top_header_rightside_options', 'socialmedia' );

								if($topheaderright =='socialicon'){ 

								    sparklestore_social_links();

								} else if($topheaderright =='topmenu'){

								    wp_nav_menu( array( 'theme_location' => 'sparkletopmenu', 'depth' => 1 ) );

								}else if($topheaderright == 'ecommerceitem'){    

									if ( class_exists( 'WooCommerce' ) ) { sparklestore_ecommerce_items(); }

								}else{

									if ( class_exists( 'WooCommerce' ) ) { sparklestore_ecommerce_items(); }
								}
							?>
			            </div>
			        </div>
		        </div>
		    </div>

		<?php }
	}
}
add_action( 'sparklestore_header', 'sparklestore_top_header', 15 );


/**
 * Main Header Area
*/
if ( ! function_exists( 'sparklestore_main_header' ) ) {
	
	function sparklestore_main_header() { ?>

		<div class="mainheader mobile-only">
			<div class="container">
				<div class="header-middle-inner">
					<?php 
						/**
						 * Menu Toggle
						*/
						do_action('sparklestore_menu_toggle'); 
					?>

			        <div class="sparklelogo">
		              	<?php 
		              		if ( function_exists( 'the_custom_logo' ) ) {
								the_custom_logo();
							} 
						?>
		              	<div class="site-branding">				              		
		              		<h1 class="site-title">
		              			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		              				<?php bloginfo( 'name' ); ?>
		              			</a>
		              		</h1>
		              		<?php
		              		$description = get_bloginfo( 'description', 'display' );
		              		if ( $description || is_customize_preview() ) { ?>
		              			<p class="site-description"><?php echo $description; ?></p>
		              		<?php } ?>
		              	</div>
			        </div><!-- End Header Logo --> 

			        <div class="rightheaderwrapend">
	    	          	<?php if ( sparklestore_is_woocommerce_activated() ) {  ?>
	    	          		<div id="site-header-cart" class="site-header-cart block-minicart sparkle-column">
								<?php echo wp_kses_post( sparklestore_shopping_cart() ); ?>
					            <div class="shopcart-description">
									<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
					            </div>
					        </div>
	    		        <?php } ?> 
			        </div>
			    </div>

			    <div class="rightheaderwrap">
		        	<div class="category-search-form">
		        	  	<?php 
		        	  		/**
		        	  		 * Normal & Advance Search
		        	  		*/
		        	  		if ( sparklestore_is_woocommerce_activated() ) {

		        	  			sparklestore_advance_product_search_form();
		        	  		}
		        	  	?>
		        	</div>
		        </div>
		        
			</div>
		</div>


		<div class="mainheader">
			<div class="container">
				<div class="header-middle-inner">
			        <div class="sparklelogo">
		              	<?php 
		              		if ( function_exists( 'the_custom_logo' ) ) {
								the_custom_logo();
							} 
						?>
		              	<div class="site-branding">				              		
		              		<h1 class="site-title">
		              			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		              				<?php bloginfo( 'name' ); ?>
		              			</a>
		              		</h1>
		              		<?php
		              		$description = get_bloginfo( 'description', 'display' );
		              		if ( $description || is_customize_preview() ) { ?>
		              			<p class="site-description"><?php echo $description; ?></p>
		              		<?php } ?>
		              	</div>
			        </div><!-- End Header Logo --> 

			        <div class="rightheaderwrap">
			        	<div class="category-search-form">
			        	  	<?php 
			        	  		/**
			        	  		 * Normal & Advance Search
			        	  		*/
			        	  		if ( sparklestore_is_woocommerce_activated() ) {

			        	  			sparklestore_advance_product_search_form();
			        	  		}else{ 
									$searchplaceholder = get_theme_mod('sparklestore_search_placeholder_text','I&#39;m searching for...' ); 
									?>
									<div class='block-search'>
									  	<form role="search" method="get" action="/" class="form-search block-search advancesearch">
											<input type="hidden" name="post_type" value="post"/>
											<div class="form-content search-box results-search">
												<div class="inner">
													<input autocomplete="off" type="text" class="input searchfield txt-livesearch" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $searchplaceholder ); ?>">
												</div>
											</div>
											<button type="submit" class="btn-submit">
												<span class="fa fa-search" aria-hidden="true"></span>
											</button>
									   	</form>
									</div>
									<?php
								  }
			        	  	?>
			        	</div>
			        </div>

			        <div class="rightheaderwrapend">
	    	          	<?php if ( sparklestore_is_woocommerce_activated() ) {  ?>
	    	          		<div id="site-header-cart" class="site-header-cart block-minicart sparkle-column">
								<?php echo wp_kses_post( sparklestore_shopping_cart() ); ?>
					            <div class="shopcart-description">
									<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
					            </div>
					        </div>
	    		        <?php } ?> 
			        </div>
			    </div>
			</div>
		</div>		    
		<?php
	}
}
add_action( 'sparklestore_header', 'sparklestore_main_header', 20 );


if ( ! function_exists( 'sparklestore_header_after' ) ) {
	/**
	 * Header Area
	 * @since  1.0.0
	 * @return void
	*/
	function sparklestore_header_after() {
		?>
			</div>
		</header><!-- #masthead -->
		<?php
	}
}
add_action( 'sparklestore_header_after', 'sparklestore_header_after', 25 );