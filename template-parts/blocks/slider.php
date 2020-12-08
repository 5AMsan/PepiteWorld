<?php

/**
 * Testimonial Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */



// Create id attribute allowing for custom "anchor" value.
$id = 'glideSlider-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'glide';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}



$items = array();
if (have_rows('galerie')) {
    ?>
    <div class="glide">
        <div data-glide-el="track" class="glide__track">
            <ul class="glide__slides">
            <?php
            while( have_rows('galerie') ) {
                the_row();
                $is_video = @get_sub_field('galerie_item_is_video', )[0] == 'Oui' ? true:false;
                $video = pepite_world_filter_video(get_sub_field('galerie_item_video', false, false)); //get_sub_field('galerie_item_video', false, false); //
                $image = wp_get_attachment_image(get_sub_field('galerie_item_image'), 'large');
                $item = $is_video ? $video : $image;
                ?>
                <li class="glide__slide">
                    <?php echo $item ?>
                </li>
                <?php
            }
            ?>
            </ul>
        </div>
        <div class="glide__arrows" data-glide-el="controls">
            <button class="glide__arrow glide__arrow--left" data-glide-dir="&lt;">Prev</button>
            <button data-glide-dir="&gt;" class="glide__arrow glide__arrow--right">Next</button>
        </div>
    </div>
    <?php
}

// Load values and assign defaults.
$text = get_field('testimonial') ?: 'Your testimonial here...';
$author = get_field('author') ?: 'Author name';
$role = get_field('role') ?: 'Author role';
$image = get_field('image') ?: 295;
$background_color = get_field('background_color');
$text_color = get_field('text_color');

?>
