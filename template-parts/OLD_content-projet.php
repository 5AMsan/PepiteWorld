<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Pépite_World
 */

?>
<div  id="post-<?php the_ID(); ?>" <?php post_class('projet'); ?>>

	<?php if(have_posts()) : while ( have_posts() ): the_post(); ?>
		<?php 
		// ACF repeater
		// var_dump('all in');
		// if( have_rows('images_a_trainer') ):
		// 	$drag_images = [];
		// 	while ( have_rows('images_a_trainer') ) : the_row();
		// 		$image = get_sub_field('drag_image');
		// 		//$drag_images[] = $image;
		// 		$format = '<img src="%s" />';

		// 		echo sprintf ($format, $image['url'] );
		// 		// Do something...

		// 	endwhile;
		// 	// var_dump($drag_images);	
		// else :
		// 	// no rows found
		// endif;
		?>
		<?php // the_content(); ?>
	<?php endwhile; endif;?>
	
</div><!-- .entry-content -->

