<!--  TEST PAGE -->
<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Pépite_World
 */

if (have_posts()) while(have_posts()): 
    the_post();
    ?>

    <?php echo do_shortcode("[fontsampler id=".get_field('font_main')."]"); ?>

    <?php echo pepite_world_infobar('fonderie') ?>

    <div class="row font-info">
        <div class="column">
            <?php the_post_thumbnail(); ?>
        </div>
        <div class="column">
            <?php the_content(); ?>
        </div>
    </div>

    <div class="row font-gallery">
        <?php pepite_world_get_post_gallery(); ?>
    </div>

    <?php

endwhile;


