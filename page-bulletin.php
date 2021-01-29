<?php
/**
 * Template Name: Page Post Bulletin
 * The template for displaying bulletin posts, comments
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage FreelanceEngine
 * @since FreelanceEngine 1.8.2
 */
global $ae_post_factory, $user_ID;
get_header();
if ( isset($_REQUEST['id']) ) {
    $post = get_post($_REQUEST['id']);
    if ( $post ) {
        $post_object = $ae_post_factory->get($post->post_type);
        $post_convert = $post_object->convert($post);
    }
}
?>

<div class="fre-page-wrapper">
    <div class="fre-page-title">
        <div clsss="container">
            <h2><?php _e('Post Detail');?></h2>
        </div>
    </div>

    <div class="fre-page-section">
        <div class="container">
            <div class="bulletin-wrap">
                <div class="fre-bulletin-box">
                    <div class="bulletin-freelance-info-wrap active">
                        <?php //if ( !empty($bulletin) ) { ?>
                            <!-- Box show bulletin post detail-->
                            <div class="freelance-bulletin-wrap">
                                <h2><?php //echo $bulletin->title ?></h2>
                                <?php //echo apply_filters( 'the_content', $bulletin->content ) ?>
                            </div>
                            <!-- End Box show bulletin post detail-->
                        <?php //} ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
