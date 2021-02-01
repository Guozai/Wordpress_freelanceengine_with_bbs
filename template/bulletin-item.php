<?php
/**
 * The template for displaying bulletin in a loop
 * @since  1.8.2
 * @package FreelanceEngine
 * @category Template
 */
global $wp_query, $ae_post_factory, $post;
$post_object = $ae_post_factory->get( BULLETIN );
$current     = $post_objecct->current_post;
if (!$current) { return; }
$tax_input   = $current->tax_input;
?>

<li class="bulletin-item">
    <div class="bulletin-list-wrap">
        <h2 class="bulletin-list-title">
            <a class="secondary-color" herf="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        </h2>
        <div class="bullein-list-info">
            <span><?php printf( __( 'Posted %s', ET_DOMAIN ), get_the_date() ); ?></span>
        </div>
        <div class="bulletin-list-desc">
            <p><?php echo $current->post_content_trim; ?></p>
        </div>
    </div>
</li>