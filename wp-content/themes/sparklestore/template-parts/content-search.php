<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package SparkleStore
 */

$postformat = get_post_format();

$post_description = get_theme_mod( 'sparklestore_post_description_options', 'excerpt' );
$post_content_type 	= apply_filters( 'sparklestore_content_type', $post_description );
$blogreadmore_btn = get_theme_mod( 'sparklestore_blogtemplate_btn', 'Read More' );


?>
<article id="post-<?php the_ID(); ?>" <?php post_class('article'); ?> itemtype="http://schema.org/BlogPosting" itemtype="http://schema.org/BlogPosting">

	<?php
        sparklestore_post_format_media( $postformat );
	?>

	<div class="box <?php if ( !has_post_thumbnail() ){ echo esc_attr( 'nopostimage' ); }?>">

		<?php 

			the_title( '<h3><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); 

			if ( 'post' === get_post_type() ){ do_action( 'sparklestore_post_meta', 10 ); } 
		?>
		
		<div class="entry-content">
			<?php
				if( is_single( ) ){

					the_content( sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'sparklestore' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					) );

				}else{

					if ( 'excerpt' === $post_content_type ) {

						the_excerpt();

					} elseif ( 'content' === $post_content_type ) {

						the_content( sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'sparklestore' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							get_the_title()
						) );
					}
				}
			?>
		</div>

		<?php if( ! is_single( ) ){ if ( 'excerpt' === $post_content_type ) { ?>
	        <div class="btns">
				<a href="<?php the_permalink(); ?>">
					<span><?php echo esc_html( $blogreadmore_btn ); ?> <i class="fa fa-angle-double-right"></i></span>
				</a>
			</div>
		<?php } } ?>
		
	</div>

</article><!-- #post-<?php the_ID(); ?> -->
