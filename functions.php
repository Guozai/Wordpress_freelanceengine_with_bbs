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
		function __construct( $post_type = 'bulletin' ) {
			$this->post_type = BULLETIN;
			// add action fetch bulletin
			//$this->add_ajax( 'ae-featch-bulletins', 'fetch_post' );
			/**
			 * sync bulletin
			 * # update , insert ...
			 *
			 * @param Array $request
			 *
			 * @since v1.8.2
			 */
			$this->add_ajax( 'ae-bulletin-sync', 'sync_post' );
			//$this->add_action( 'pre_get_posts', 'pre_get_bulletin' );
			/**
			 * hook convert a bulletin to add custom meta data
			 *
			 * @param Object $result bulletin object
			 *
			 * @since v1.8.2
			 */
			$this->add_filter( 'ae_convert_bulletin', 'ae_convert_bulletin' );

			// add comment post meta

			// modify comment post meta

			// hook to groupy by, group bulletin by author
			//$this->add_filter( 'posts_groupby', 'posts_groupby', 10, 2 );
			// filter post where to check bulletin title
			//$this->add_filter( 'posts_search', 'fre_posts_search', 10, 2 );
			// add filter posts join to join post meta and get et professional title
			//$this->add_filter( 'posts_join', 'fre_join_post', 10, 2 );
			// add filter groupby, group bulletin by post_category
			//$this->add_filter( 'posts_groupby', 'fre_posts_group_by', 10, 2 );
			// Delete bulletin after admin delete user
			//$this->add_action( 'remove_user_from_blog', 'fre_delete_bulletin_after_delete_user' );
			// delete comment
			//$this->add_ajax( 'ae-bulletin-delete-meta', 'deleteMetaBulletin' );
		}

		/**
		 * convert bulletin
		 * @package FreelanceEngine
		 */
		function ae_convert_bulletin( $result ) {

			return $result;
		}

		/**
	 	 * ajax callback sync post details
		 * - update
		 * - insert
		 * - delete
		 */
		function sync_post() {
			global $ae_post_factory, $user_ID;
			$request 	= $_REQUEST;

			if ( ! AE_Users::is_activate( $user_ID ) ) {
				wp_send_json( array(
					'success' => false,
					'msg'     => __( "Your account is pending. You have to activate your account to continue this step.", ET_DOMAIN )
				) );
			};

			// prevent customers submit posts
			if ( ! fre_share_role() && ae_user_role() == EMPLOYER ) {
				wp_send_json( array(
					'success' => false,
					'msg'     => __( "You need an lawyer account to submit a bulletin post.", ET_DOMAIN )
				) );
			}

			// set status for bulletin
			if ( ! isset( $request['post_status'] ) ) {
				$request['post_status'] = 'publish';
			}

			// version 1.8.2 retrieve bulletin
			if ( isset( $request['bulletin'] ) and ! empty( $request['bulletin'] ) ) {
				$bulletin = array(
					'post_title' => $request['bulletin']['title'],
					'post_content' => $request['bulletin']['content'],
					'post_author' => $user_ID,
					'post_status' => $request['post_status'],
				);
				if ($request['method'] === 'create') {
					$status = wp_insert_post($bulletin);
					$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
					$update = add_post_meta( $profile_id, 'post_category', $request['bulletin']['category'] );
					$status = $status && $update;
					$update = add_post_meta( $profile_id, 'post_language', $request['bulletin']['language'] );
					$status = $status && $update;
				} else if ($request['method'] === 'update') {

				}
				if ($status === false) {
					wp_send_json( array(
						'success' => false,
						'msg'     => __( "Failed to create new post.", ET_DOMAIN )
					) );
				}
			}
		}

		/**
	 	 * Get post_category
	 	 */
		public function fre_get_category() {
			$terms = get_terms( 'post_category', array(
				'hide_empty' => 0,
				'fields'     => 'names'
			) );
			wp_send_json( $terms );
		}
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

function freelanceengine_child_scripts() {
	// Dequeue (remove) parent theme script
	wp_dequeue_script( 'front' );
	wp_deregister_script( 'front' );

	wp_register_script( 'front', get_stylesheet_directory_uri() . '/js/front.js', array( 
		'jquery',
		'underscore',
		'backbone',
		'appengine',
		'fre-lib' 
	), ET_VERSION, true );

	// enqueue replacement child theme script
	wp_enqueue_script( 'front' );
	// script edit bulletin
	if ( is_page_template( 'page-bulletin.php' ) || is_author() || et_load_mobile() ) {
		// register child page-bulletin.php script
		wp_register_script( 'bulletin', get_stylesheet_directory_uri() . '/js/bulletin.js', array( 
			'jquery',
			'underscore',
			'backbone',
			'appengine',
			'front' 
		), ET_VERSION, true );

		// enqueue replacement child theme script
		wp_enqueue_script( 'bulletin' );
	}
}

add_action( 'wp_enqueue_scripts', 'freelanceengine_child_scripts', 100 );