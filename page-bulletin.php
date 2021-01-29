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
global $user_ID;
get_header();

?>
<div class="fre-page-wrapper">
    <div class="fre-page-title">
        <div clsss="container">
            <h2><?php _e('Post Detail');?></h2>
        </div>
    </div>

    <div class="fre-page-section">
        <div class="container">
            <div class="page-post-bulletin-wrap" id="post-place">
                <?php
                    
                ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();