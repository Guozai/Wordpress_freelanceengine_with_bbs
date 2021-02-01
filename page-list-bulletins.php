<?php
/**
 * Template Name: List bulletins
 * Page to list bulletin posts and comments
 * This is the main page for the bbs feature
 *
 * @package WordPress
 * @subpackage FreelanceEngine
 * @since FreelanceEngine 1.8.2
 */
global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object = $ae_post_factory->get(BULLETIN);
//query_posts(array('post_type' => 'bulletin' , 'post_status' => 'publish'));
get_header();
$count_posts = wp_count_posts( BULLETIN );
$user_role   = ae_user_role( $user_ID );
?>
<div class="fre-page-wrapper">
    <div class="fre-page-title">
        <div class="container">
            <h2><?php the_title();?></h2>
        </div>
    </div>
    <div class="fre-page-section section-archive-bulletin">
        <div class="container">
            <div class="page-bulletin-list-wrap">
                <div class="fre-bulletin-list-wrap">
                    <?php //get_template_part('template/filter', 'bulletins' ); ?>
                    <div class="fre-bulletin-list-box">
                        <div class="fre-bulletin-list-wrap">
                            <div class="fre-bulletin-result-sort">
                                <div class="row">
                                    <div class="col-sm-6 col-sm-push-6">
                                        <div class="fre-bulletin-sort">
                                            <select class="fre-chosen-single sort-order" id="bulletin_orderby" name="orderby" >
                                                <option value="date"><?php _e('Newest Bulletins first',ET_DOMAIN);?></option>
                                                <option value="et_budget"><?php _e('Budget Bulletins first',ET_DOMAIN);?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-sm-pull-6">
                                        <div class="fre-bulletin-result">
                                            <p>
                                                <?php
                                                    $found_posts = '<span class="found_post">'.$wp_query->found_posts.'</span>';
                                                    $plural = sprintf(__('%s bulletins found',ET_DOMAIN), $found_posts);
                                                    $singular = sprintf(__('%s bulletin found',ET_DOMAIN),$found_posts);
                                                ?>
                                                <span class="plural <?php if( $wp_query->found_posts <= 1 ) { echo 'hide'; } ?>" >
                                                    <?php echo $plural; ?>
                                                </span>
                                                <span class="singular <?php if( $wp_query->found_posts > 1 ) { echo 'hide'; } ?>">
                                                    <?php echo $singular; ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php get_template_part( 'list', 'bulletins' ); ?>
                        </div>
                    </div>
                    <?php 
                        $wp_query->query = array_merge(  $wp_query->query ,array('is_archive_bulletin' => is_post_type_archive(BULLETIN) ) ) ;
                        echo '<div class="fre-paginations paginations-wrapper">';
                        ae_pagination($wp_query, get_query_var('paged'));
                        echo '</div>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();