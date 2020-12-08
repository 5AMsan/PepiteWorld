<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Pépite_World
 */
if (have_posts()) while(have_posts()): the_post();
?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php pepite_world_nav_projet(true) ?>
	
	<?php echo pepite_world_infobar('projet') ?>

	<div class="entry-content">
		<?php //Dynamically populate through `add_action('the_content', 'pepite_world_drag_images')` ?>
		<?php the_content();  ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->

<?php
endwhile;
