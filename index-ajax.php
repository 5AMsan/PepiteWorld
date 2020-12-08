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

?>

<?php
if (is_archive() ) {
    switch ($post->post_type) {
        case 'projet':
            $content = pepite_world_archive_projet();
            break;
        case 'font':
            $content = pepite_world_archive_font();
            break;
        case 'edition':
            $content = pepite_world_archive_edition();
            break;
        default:
            $content = apply_filters( 'the_content', get_the_content() );
            break;
    }
} else {
    // var_dump($post);
    $content = apply_filters( 'the_content', get_the_content() );
}

$response = array(
    'content' => $content,
);
wp_send_json( $response );
wp_die();

