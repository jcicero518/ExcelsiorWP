<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Client_Rest
 * @subpackage Client_Rest/admin_cpt
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Client_Rest
 * @subpackage Client_Rest/admin_cpt
 * @author     Your Name <email@example.com>
 */
class Client_Rest_Admin_CPT {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
  }
  
  public function register_cpt_event() {
    $event_cpt_labels =  array(
      'name'                => _x( 'Events', 'Post Type General Name', 'bones' ),
      'singular_name'       => _x( 'Event', 'Post Type Singular Name', 'bones' ),
      'menu_name'           => __( 'Events', 'bones' ),
      'name_admin_bar'      => __( 'Events', 'bones' ),
      'parent_item_colon'   => __( 'Parent Event:', 'bones' ),
      'all_items'           => __( 'All Events', 'bones' ),
      'add_new_item'        => __( 'Add New Event', 'bones' ),
      'add_new'             => __( 'Add New', 'bones' ),
      'new_item'            => __( 'New Event', 'bones' ),
      'edit_item'           => __( 'Edit Event', 'bones' ),
      'update_item'         => __( 'Update Event', 'bones' ),
  		'view_item'           => __( 'View Event', 'bones' ),
  		'search_items'        => __( 'Search events', 'bones' ),
  		'not_found'           => __( 'Not found', 'bones' ),
  		'not_found_in_trash'  => __( 'Not found in Trash', 'bones' ),
    );
  
    $event_cpt_args = array(
  		'label'               => __( 'event', 'bones' ),
  		'description'         => __( 'CDRPC Events', 'bones' ),
  		'labels'              => $event_cpt_labels,
  		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'revisions', 'custom-fields' ),
  		'taxonomies'          => array( 'category', 'post_tag' ),
  		'hierarchical'        => false,
  		'public'              => true,
  		'show_ui'             => true,
  		'show_in_menu'        => true,
  		'menu_position'       => 5,
  		//'menu_icon'           => 'clipboard',
  		'show_in_admin_bar'   => true,
  		'show_in_nav_menus'   => true,
  		'can_export'          => true,
  		'has_archive'         => true,
  		'exclude_from_search' => false,
  		'publicly_queryable'  => true,
  		'capability_type'     => 'post',
    );
    
    register_post_type( 'event', $event_cpt_args );
  }
  
}