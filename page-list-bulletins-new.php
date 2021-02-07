<?php
/**
 * Template Name: List bulletins New
 * Page to list bulletin posts and comments
 * This is the main page for the bbs feature
 * Lawyers and clients can comment on this page
 *
 * @package WordPress
 * @subpackage FreelanceEngine
 * @since FreelanceEngine 1.8.2
 */
global $ae_post_factory, $user_ID;
// convert current bulletin
$post_object = $ae_post_factory->get( BULLETIN );

$bulletin_posts = get_posts( array(
    //'author'      => $user_ID,
    'post_type'   => 'bulletin'
) );
$bulletins = array();
foreach ( $bulletin_posts as $bulletin_post ) {
    if ( $bulletin_post && !is_wp_error( $bulletin_post ) ) {
        $bulletins[] = $post_object->convert( $bulletin_post );
    }
}

$is_edit = true;
get_header();
?>

<div class="fre-page-wrapper">
    <div class="fre-page-section">
        <div class="container">
            <div class="list-bulletin-wrapper">
                <?php if ( $is_edit or !empty( $bulletins )) { ?>
                <div class="fre-bulletin-box">
                    <div class="bulletin-freelance-info-wrap active">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <h2 class="freelance-bulletin-title"><?php _e( 'Bulletin Board', ET_DOMAIN ) ?></h2>
                            </div>

                            <span id="fre-empty-bulletin">
                                <?php if ( $is_edit ) { ?>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="freelance-bulletin-add">
                                        <a herf="javascript:void(0)"
                                           class="fre-normal-btn-o bulletin-show-edit-tab-btn"
                                           data-ctn_edit="ctn-edit-bulletin" data-ctn_hide="fre-empty-bulletin">
                                            <?php _e( 'Add new', ET_DOMAIN ) ?>
                                        </a>
                                    </div>
                                </div>
                                <?php } ?>
                                <p class="col-xs-12 fre-empty-optional-bulletin" <?php echo (empty($bulletins) and $is_edit) ? '' : 'style="display : none"' ?>>
                                    <?php _e('Add new post.', ET_DOMAIN) ?>
                                </p>
                            </span>
                        </div>

                        <ul class="freelance-bulletin-list">
				            <?php if ( $is_edit ) { ?>
                                <!-- Box add new bulletin post-->
                                <li class="freelance-bulletin-new-wrap cnt-bulletin-hide" id="ctn-edit-bulletin">
                                    <div class="freelance-bulletin-new">
                                        <form class="fre-bulletin-form freelance-bulletin-form freelance-bulletin-form-save" method="post">

                            	            <div class="fre-input-field">
                                                <input type="text" name="bulletin[title]"
                                                       placeholder="<?php _e( 'Title', ET_DOMAIN ) ?>">
                                            </div>

                                            <div class="fre-input-field">
                                                <?php
                                                    $results = $wpdb->get_results( "SELECT term_id FROM " . $wpdb->term_taxonomy . " WHERE taxonomy = 'project_category'" );

                                                    if( !empty($results) ) {
                                                        $category_arr = array();
                                                        foreach ($results as $result) {
                                                            $category = array();
                                                            $category["name"] = $wpdb->get_var( "SELECT name FROM " . $wpdb->terms . " WHERE term_id = " . $result->term_id);
                                                            $category["slug"] = $wpdb->get_var( "SELECT slug FROM " . $wpdb->terms . " WHERE term_id = " . $result->term_id);
                                                            array_push($category_arr, $category);
                                                        }
                                                    }
                                                ?>
                                                <select data-chosen-width="100%" data-validate_filed="1" data-chosen-disable-search data-placeholder="Choose category" name="bulletin[category]" id="post_category" class='fre-chosen-single' style="display: none;">
                                                    <?php
                                                        echo ("<option value=''>Choose post category</option>");
                                                        foreach($category_arr as $category){
                                                            echo ("<option value='" . $category['slug'] . "'> " . $category['name'] . "</option>");
                                                        }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="fre-input-field">
                                                <?php
                                                    // get the term_ids of all post_languages
                                                    $results = $wpdb->get_results( "SELECT term_id FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'post_language'" );
                                                    // get the language slug array pair
                                                    $language_arr = array();
                                                    if ( !empty($results) ) {
                                                        foreach ($results as $result) {
                                                            $language_arr[] = $wpdb->get_var( "SELECT name FROM " . $wpdb->terms . " WHERE term_id = " . $result->term_id );
                                                            //$language_slug_arr[$i] = $wpdb->get_var( "SELECT slug FROM " . $wpdb->terms . " WHERE term_id = " . $result->term_id);
                                                        }
                                                    }
                                                ?>
                                                <select data-chosen-width="100%" data-validate_filed="1" data-chosen-disable-search data-placeholder="Choose post language" name="bulletin[language]" id="post_language" class='fre-chosen-single' style="display: none;">
                                                    <?php
                                                        echo ("<option value=''>Choose Bulletin Post Language</option>");
                                                        foreach($language_arr as $language){
                                                            echo ("<option value='" . $language . "'>$language</option>");
                                                        }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="fre-input-field no-margin-bottom">
                                                <textarea name="bulletin[content]" id="" cols="30" rows="10" placeholder="<?php _e('Starting writing post here',ET_DOMAIN) ?>"></textarea>
                                            </div>

                                            <div class="fre-form-btn">
                                                <input type="submit" class="fre-normal-btn btn-submit" name=""
                                                       value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                                <span class="fre-bulletin-close bulletin-show-edit-tab-btn"
                                                      data-ctn_edit="fre-empty-bulletin"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                                <!-- End Box add new bulletin post-->
				            <?php } ?>

				            <?php if ( ! empty( $bulletins ) ) {
					            foreach ( $bulletins as $k => $bulletin ) {
						            if ( ! empty( $bulletin ) ) {
                                        $post_language = get_post_meta( $bulletin->id, 'post_language', true );
								        ?>

                                            <!-- Box show bulletin posts-->
                                            <li class="cnt-bulletin-hide meta_history_item_<?php echo $bulletin->id ?>"
                                                id="cnt-bulletin-default-<?php echo $bulletin->id ?>"
                                                style="<?php echo $k + 1 == count( $bulletins ) ? 'border-bottom: 0;padding-bottom: 0;' : '' ?>">
                                                <div class="freelance-bulletin-wrap">
                                    	            <h2><?php echo $bulletin->post_title ?></h2>
                                                    <div class="freelance-bulletin-attributes">
                                                        <span class="freelance-empty-info">
                                                            <?php echo !empty( $bulletin->project_category ) ? $bulletin->project_category : '<i>' . __( 'No category information', ET_DOMAIN ) . '</i>'; ?>
                                                        </span>
                                                        <span class="freelance-empty-info">
                                                            <?php echo !empty( $post_language ) ? $post_language : '<i>' . __( 'No language information', ET_DOMAIN ) . '</i>'; ?>
                                                        </span>
                                                    </div>
                                                    <?php echo apply_filters( 'the_content', $bulletin->post_content ) ?>
                                                </div>
									            <?php if ( $is_edit ) { ?>
                                                    <div class="freelance-bulletin-action">
                                                        <a href="javascript:void(0)" class="bulletin-show-edit-tab-btn"
                                                           data-ctn_edit="ctn-edit-bulletin-<?php echo $bulletin->id ?>">
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												            <?php _e( 'Edit', ET_DOMAIN ) ?>
                                                        </a>
                                                        <a href="javascript:void(0)" class="remove_history_fre" data-id="<?php echo $bulletin->id ?>">
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i><?php _e('Remove',ET_DOMAIN) ?></a>
                                                    </div>
								            	<?php } ?>
                                                <div class="freelance-bulletin-action">
                                                    <a href="javascript:void(0)" class="bulletin-comment-btn" 
                                                    data-ctn_comment="ctn-comment-bulletin-" data-ctn_hide="fre-empty-comment" data-id="<?php echo $bulletin->id ?>">
                                                        <i class="fa fa-comment-o"></i><?php _e('Comment', ET_DOMAIN) ?></a>
                                                </div>
                                            </li>
                                            <!-- End Box show bulletin posts-->



								            <?php if ( $is_edit ) { ?>
                                                <!-- Box edit bulletin post-->
                                                <li class="freelance-bulletin-new-wrap cnt-bulletin-hide meta_history_item_<?php echo $bulletin->id ?>"
                                                    id="ctn-edit-bulletin-<?php echo $bulletin->id ?>">
                                                    <div class="freelance-bulletin-new">
                                                        <form class="fre-bulletin-form freelance-bulletin-form freelance-bulletin-form-edit"
                                                              method="post">

                                                            <div class="fre-input-field">
                                                                <input type="text" name="bulletin[title]"
                                                                       placeholder="<?php _e( 'Title', ET_DOMAIN ) ?>"
                                                                       value="<?php echo $bulletin->post_title ?>">
                                                            </div>

                                                            <div class="fre-input-field">
                                                                <?php
                                                                    $results = $wpdb->get_results( "SELECT term_id FROM " . $wpdb->term_taxonomy . " WHERE taxonomy = 'project_category'" );

                                                                    if( !empty($results) ) {
                                                                        $category_arr = array();
                                                                        foreach ($results as $result) {
                                                                            $category = array();
                                                                            $category["name"] = $wpdb->get_var( "SELECT name FROM " . $wpdb->terms . " WHERE term_id = " . $result->term_id);
                                                                            $category["slug"] = $wpdb->get_var( "SELECT slug FROM " . $wpdb->terms . " WHERE term_id = " . $result->term_id);
                                                                            array_push($category_arr, $category);
                                                                        }
                                                                    }
                                                                ?>
                                                                <select data-chosen-width="100%" data-validate_filed="1" data-chosen-disable-search data-placeholder="Choose category" name="bulletin[category]" id="post_category" class='fre-chosen-single' style="display: none;">
                                                                    <?php
                                                                        echo ("<option value=''>Choose post category</option>");
                                                                        foreach($category_arr as $category){
                                                                            if ( !empty($bulletin->project_category) && ($bulletin->project_category === $category['slug']))
                                                                                echo ("<option value='" . $category['slug'] ."' selected>" . $category['name'] . "</option>");
                                                                            else
                                                                                echo ("<option value='" . $category['slug'] . "'> " . $category['name'] . "</option>");
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div class="fre-input-field">
                                                                <?php
                                                                    // get the term_ids of all post_languages
                                                                    $results = $wpdb->get_results( "SELECT term_id FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'post_language'" );
                                                                    // get the language slug array pair
                                                                    $language_arr = array();
                                                                    if ( !empty($results) ) {
                                                                        foreach ($results as $result) {
                                                                            $language_arr[] = $wpdb->get_var( "SELECT name FROM " . $wpdb->terms . " WHERE term_id = " . $result->term_id );
                                                                            //$language_slug_arr[$i] = $wpdb->get_var( "SELECT slug FROM " . $wpdb->terms . " WHERE term_id = " . $result->term_id);
                                                                        }
                                                                    }
                                                                ?>
                                                                <select data-chosen-width="100%" data-validate_filed="1" data-chosen-disable-search data-placeholder="Choose post language" name="bulletin[language]" id="post_language" class='fre-chosen-single' style="display: none;">
                                                                    <?php
                                                                        echo ("<option value=''>Choose Bulletin Post Language</option>");
                                                                        foreach($language_arr as $language){
                                                                            if ( !empty($post_language) && ($post_language === $language) )
                                                                                echo ("<option value='" . $language ."' selected>$language</option>");
                                                                            else
                                                                                echo ("<option value='" . $language . "'>$language</option>");
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div class="fre-input-field no-margin-bottom">
                                                                <textarea name="bulletin[content]" id="" cols="30"  placeholder="<?php _e('Start writing post here',ET_DOMAIN) ?>"
                                                                  rows="10"><?php echo ! empty( $bulletin->post_content ) ? $bulletin->post_content : '' ?></textarea>
                                                            </div>

                                                            <div class="fre-form-btn">
                                                                <input type="submit" class="fre-normal-btn btn-submit" name="" data-id="<?php echo $bulletin->id ?>"
                                                                       value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                                                <span class="fre-bulletin-close bulletin-show-edit-tab-btn"
                                                                      data-ctn_edit="cnt-bulletin-default-<?php echo $bulletin->id ?>"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </li>
                                                <!-- Box edit bulletin post-->
                                            <?php } ?>
                                                <!-- Box comment-->
                                                <li class="freelance-bulletin-new-wrap cnt-comment-hide meta_history_item_<?php echo $bulletin->id ?>"
                                                    id="ctn-comment-bulletin-<?php echo $bulletin->id ?>" data-id="<?php echo $bulletin->id ?>">
                                                    <div class="freelance-bulletin-new">
                                                        <form class="fre-bulletin-form freelance-bulletin-form freelance-bulletin-form-edit"
                                                                method="post">

                                                            <div class="fre-input-field no-margin-bottom">
                                                                <textarea name="bulletin[content]" id="" cols="30"  placeholder="<?php _e('Comment here',ET_DOMAIN) ?>"
                                                                    rows="10"></textarea>
                                                            </div>

                                                            <div class="fre-form-btn">
                                                                <input type="submit" class="fre-normal-btn btn-submit" name="" data-id="<?php echo $bulletin->id ?>"
                                                                        value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                                                <span class="fre-bulletin-close bulletin-show-edit-tab-btn"
                                                                        data-ctn_edit="cnt-bulletin-default-<?php echo $bulletin->id ?>"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </li>
                                                <!-- Box comment -->
						            <?php }
					            }
				            } ?>
                        </ul>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();