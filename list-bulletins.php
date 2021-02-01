<?php
/**
 * Template list bulletins
 */
global $wp_query, $ae_post_factory, $post;
$post_object = $ae_post_factory->get( BULLETIN );
?>
<ul class="fre-bulletin-list bulletin-list-container">
    <?php
        $postdata = array();
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                $convert    = $post_object->convert( $post );
                $postdata[] = $convert;
                get_template_part( 'template/bulletin', 'item' );
            }
        }
    ?>
</ul>
