<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Sparkle_Store
 */

	do_action( 'sparklestore_footer_before');	

		/**
		 * @see  sparklestore_footer_widget_area() - 10
		*/
		do_action( 'sparklestore_footer_widget');

    	/**
    	 * Top Footer Area
    	 * Two different filters
    	 * @see  sparklestore_social_links() - 5
    	 * @see  sparklestore_payment_logo() - 10
    	*/
    	do_action( 'sparklestore_top_footer');

    	/**
    	 * Bottom Footer Area
    	 * @social icon filters : sparklestore_footer_menu() - 5
    	*/
    	do_action( 'sparklestore_bottom_footer');  
    
     do_action( 'sparklestore_footer_after');	 
?>	    

</div><!-- #page -->

<a href="#" class="scrollup">
	<i class="fa icofont-bubble-up"></i>
</a>

<?php wp_footer(); ?>
</div> <!-- main -->
</body>

</html>
