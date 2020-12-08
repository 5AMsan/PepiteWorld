<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Pépite_World
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <?php //pepite_world_nav_projet() ?>

	<div class="entry-content">
		<?php 
		// // ACF repeater
		// var_dump('all in');
		// if( have_rows('images_a_trainer') ):
		// 	$drag_images = [];
		// 	while ( have_rows('images_a_trainer') ) : the_row();
		// 		$image = get_sub_field('drag_image');
		// 		$drag_images[] = $image;

		// 		// Do something...
		// 	endwhile;
		// 	var_dump($drag_images);	
		// else :
		// 	// no rows found
		// endif;
		?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
