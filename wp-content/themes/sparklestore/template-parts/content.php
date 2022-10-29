<?php
/**
 * Template part for displaying posts.
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
<article id="post-<?php the_ID(); ?>" <?php post_class('article text-center'); ?> itemtype="http://schema.org/BlogPosting" itemtype="http://schema.org/BlogPosting">

	<?php sparklestore_post_format_media( $postformat ); ?>

	<div class="box">
		<?php 

			the_title( '<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); 

			if ( 'post' === get_post_type() ){ do_action( 'sparklestore_post_meta', 10 ); } 
		?>
		
		<div class="entry-content">
			<?php
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
			?>
		</div>

		<?php if ( 'excerpt' === $post_content_type ) { ?>
	        <div class="btn-wrap">
				<a class="btn btn-primary" href="<?php the_permalink(); ?>">
					<span><?php echo esc_html( $blogreadmore_btn ); ?> <i class="icofont-double-right"></i></span>
				</a>
			</div>
		<?php } ?>
		
	</div>

</article><!-- #post-<?php the_ID(); ?> -->