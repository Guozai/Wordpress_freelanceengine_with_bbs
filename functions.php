<?php
define( 'BULLETIN', 'bulletin' );

/**
 * Corresponding to start of bulletins.php
 * Registers a new post type Bulletin
 * @uses $wp_post_types Inserts new post type object into the list
 *
 * @param string  Post type key, must not exceed 20 characters
 * @param array|string See optional args description above.
 *
 * @return object|WP_Error the registered post type object, or an error object
 */
function fre_register_bulletin() {

    $labels = array(
        'name'               => __( 'Bulletins', ET_DOMAIN ),
        'singular_name'      => __( 'Bulletin', ET_DOMAIN ),
        'add_new'            => _x( 'Add New bulletin', ET_DOMAIN, ET_DOMAIN ),
        'add_new_item'       => __( 'Add New bulletin', ET_DOMAIN ),
        'edit_item'          => __( 'Edit bulletin', ET_DOMAIN ),
		'new_item'           => __( 'New bulletin', ET_DOMAIN ),
		'view_item'          => __( 'View bulletin', ET_DOMAIN ),
		'search_items'       => __( 'Search Bulletins', ET_DOMAIN ),
		'not_found'          => __( 'No Bulletins found', ET_DOMAIN ),
		'not_found_in_trash' => __( 'No Bulletins found in Trash', ET_DOMAIN ),
		'parent_item_colon'  => __( 'Parent bulletin:', ET_DOMAIN ),
		'menu_name'          => __( 'Bulletins', ET_DOMAIN ),
    );
    $args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'show_in_admin_bar' => true,
		'menu_position'     => 7,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => ae_get_option( 'fre_bulletin_archive', 'bulletins' ),
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => array('slug' => ae_get_option('fre_project_slug', 'bulletin')),
		'capability_type'     => 'post',
		'supports'            => array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt',
			'custom-fields',
			'trackbacks',
			'comments',
			'revisions',
			'page-attributes',
			'post-formats'
		)
	);
	register_post_type( BULLETIN, $args );
	$labels = array(
		'name'                  => _x( 'Countries', 'Taxonomy plural name', ET_DOMAIN ),
		'singular_name'         => _x( 'Country', 'Taxonomy singular name', ET_DOMAIN ),
		'search_items'          => __( 'Search countries', ET_DOMAIN ),
		'popular_items'         => __( 'Popular countries', ET_DOMAIN ),
		'all_items'             => __( 'All countries', ET_DOMAIN ),
		'parent_item'           => __( 'Parent country', ET_DOMAIN ),
		'parent_item_colon'     => __( 'Parent country', ET_DOMAIN ),
		'edit_item'             => __( 'Edit country', ET_DOMAIN ),
		'update_item'           => __( 'Update country ', ET_DOMAIN ),
		'add_new_item'          => __( 'Add New country ', ET_DOMAIN ),
		'new_item_name'         => __( 'New country Name', ET_DOMAIN ),
		'add_or_remove_items'   => __( 'Add or remove country', ET_DOMAIN ),
		'choose_from_most_used' => __( 'Choose from most used enginetheme', ET_DOMAIN ),
		'menu_name'             => __( 'Countries', ET_DOMAIN ),
    );
    
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'show_tagcloud'     => true,
		'show_ui'           => true,
		'query_var'         => true,
		'rewrite'           => array(
            'slug' => ae_get_option( 'project_category_slug', 'project_category' ),
            'hierarchical' => ae_get_option( 'project_category_hierarchical', false )
		),
		'capabilities'      => array(
			'manage_terms',
			'edit_terms',
			'delete_terms',
			'assign_terms'
		)
    );
    
    register_taxonomy( 'project_category', array(
		PROJECT,
        PROFILE,
        BULLETIN
    ), $args );
    
    global $ae_post_factory;
    $bulletin_tax = array( 'project_category' );
    $bulletin_meta = array( 'comment' );
	$ae_post_factory->set( BULLETIN, new AE_Posts( BULLETIN, $bulletin_tax, $bulletin_meta ) );
}

add_action( 'init', 'fre_register_bulletin', 25 );

/**
 * register post type bulletin.
 */
function setup_child_theme_classes() {
	class Fre_BulletinAction extends AE_PostAction {

		
	}

	new Fre_BulletinAction();
}

add_action( 'after_setup_theme', 'setup_child_theme_classes');

/**
 * end of bulletins.php
 ***********************************************************************************************************/

/**
 * enqueue child styles
 */
function freelanceengine_child_styles() {
	wp_dequeue_style( 'main-style' );
	wp_deregister_style( 'main-style' );

	wp_register_style( 'main-style', get_stylesheet_directory_uri() .'/style.css', array());
	wp_enqueue_style('main-style' );	
}

add_action( 'wp_enqueue_styles', 'freelanceengine_child_styles', 100 );