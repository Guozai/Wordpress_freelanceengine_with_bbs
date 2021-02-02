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

			// version 1.8.2 set display name when update profile
			if ( isset( $request['display_name'] ) and ! empty( $request['display_name'] ) ) {
				wp_update_user( array( 'ID' => $user_ID, 'display_name' => $request['display_name'] ) );
			}
			if ( isset( $request['work_experience'] ) && ! empty( $request['work_experience'] ) && is_array( $request['work_experience'] ) ) {
				$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
				$experience = $request['work_experience'];
				if ( ! empty( $experience['title'] ) && ! empty( $experience['subtitle'] ) ) {
					if ( ! empty( $experience['id'] ) ) {
						$meta_id = $experience['id'];
						unset( $experience['id'] );
						global $wpdb;
						$update = $wpdb->update( $wpdb->postmeta, array( 'meta_value' => serialize( $experience ) ), array( 'meta_id' => $meta_id ) );
					} else {
						$update = add_post_meta( $profile_id, 'work_experience', serialize( $experience ) );
					}
					if ( $update === false ) {
						wp_send_json( array(
							'success' => false,
							'msg'     => __( "Update Experience fail.", ET_DOMAIN )
						) );
					}
				}
			}
			if ( isset( $request['certification'] ) && ! empty( $request['certification'] ) && is_array( $request['certification'] ) ) {
				$profile_id    = get_user_meta( $user_ID, 'user_profile_id', true );
				$certification = $request['certification'];
				if ( ! empty( $certification['title'] ) && ! empty( $certification['subtitle'] ) ) {
					if ( ! empty( $certification['id'] ) ) {
						$meta_id = $certification['id'];
						unset( $certification['id'] );
						global $wpdb;
						$update = $wpdb->update( $wpdb->postmeta, array( 'meta_value' => serialize( $certification ) ), array( 'meta_id' => $meta_id ) );
					} else {
						$update = add_post_meta( $profile_id, 'certification', serialize( $certification ) );
					}
					if ( $update === false ) {
						wp_send_json( array(
							'success' => false,
							'msg'     => __( "Update Fertification fail.", ET_DOMAIN )
						) );
					}
				}
			}
			if ( isset( $request['education'] ) && ! empty( $request['education'] ) && is_array( $request['education'] ) ) {
				$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
				$education  = $request['education'];
				if ( ! empty( $education['title'] ) && ! empty( $education['subtitle'] ) ) {
					if ( ! empty( $education['id'] ) ) {
						$meta_id = $education['id'];
						unset( $education['id'] );
						global $wpdb;
						$update = $wpdb->update( $wpdb->postmeta, array( 'meta_value' => serialize( $education ) ), array( 'meta_id' => $meta_id ) );
					} else {
						$update = add_post_meta( $profile_id, 'education', serialize( $education ) );
					}
					if ( $update === false ) {
						wp_send_json( array(
							'success' => false,
							'msg'     => __( "Edit Education Fail.", ET_DOMAIN )
						) );
					}
				}
			}
			// set profile title
			$request['post_title'] = ! empty( $request['display_name'] ) ? $request['display_name'] : $user_data->display_name;
			if ( $request['method'] == 'create' ) {
				$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
				if ( $profile_id ) {
					$profile_post = get_post( $profile_id );
					if ( $profile_post && $profile_post->post_status != 'draft' ) {
						wp_send_json( array(
								'success' => false,
								'msg'     => __( "You only can have on profile.", ET_DOMAIN )
							)
						);
					}
				}
			}
			$email_skill = 0;
			if ( isset( $request['email_skill'] ) ) {

				if ( ! empty( $request['email_skill'] ) ) {

					if ( is_array( $request['email_skill'] ) ) {
						$email_skill = ! empty( $request['email_skill'][0] ) ? $request['email_skill'][0] : 0;
					} else {
						$email_skill = $request['email_skill'];
					}
				} else {
					$email_skill = 0;
				}
				$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
				update_post_meta( $profile_id, 'email_skill', $email_skill );
			}
			do_action('before_sync_profile', $request);
			// sync profile
			$result = $profile->sync( $request );
			if ( ! is_wp_error( $result ) ) {
				$result->redirect_url = $result->permalink;
				$rating_score         = get_post_meta( $result->ID, 'rating_score', true );
				if ( ! $rating_score ) {
					update_post_meta( $result->ID, 'rating_score', 0 );
				}
				$user_available = get_user_meta( $user_ID, 'user_available', true );
				update_post_meta( $result->ID, 'user_available', $user_available );
				// action create profile
				if ( $request['method'] == 'create' ) {
					//update_post_meta( $result->ID,'hour_rate', 0);//@author: danng  fix query meta in page profiles search in version 1.8.4
					update_post_meta( $result->ID, 'total_projects_worked', 0 );

					$profile_id = get_user_meta( $user_ID, 'user_profile_id', true ); // 1.8.6.1
					update_post_meta( $profile_id, 'email_skill', $email_skill );  // 1.8.6.1
					// store profile id to user meta
					$response = array(
						'success' => true,
						'data'    => $result,
						'msg'     => __( "Your profile has been created successfully.", ET_DOMAIN )
					);
					wp_send_json( $response );
					//action update profile
				} else if ( $request['method'] == 'update' ) {
					$response = array(
						'success' => true,
						'data'    => $result,
						'msg'     => __( "Your profile has been updated successfully.", ET_DOMAIN )
					);
					wp_send_json( $response );
				}
			} else {
				wp_send_json( array(
					'success' => false,
					'data'    => $result,
					'msg'     => $result->get_error_message()
				) );
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