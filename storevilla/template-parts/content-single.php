<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Store_Villa
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('storevilla-blog'); ?>>

	<?php 
		if( has_post_thumbnail() ){
			$image = get_the_post_thumbnail_url(get_the_ID(), 'storevilla-blog-image');
	?>
		<figure>
			<img src="<?php echo esc_url( $image ); ?>" alt="<?php the_title(); ?>">
			<div class="sv-img-hover">
				<div class="holder">				
				</div>
			</div>		
		</figure>

	<?php } ?>

	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

	<ul class="blog-meta">
		<li class="sv-author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a></li>
		<li class="sv-time"><?php the_date(); ?></li>
		<li class="sv-category"><?php the_category( ', ' ); ?></li>
		<li class="sv-tags"><?php the_tags(''); ?></li>
		<li class="sv-comments"><?php comments_popup_link( '0 Comment', '1 Comment', '% Comments' ); ?></li>
	</ul>

	<div class="entry-content">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'storevilla' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
