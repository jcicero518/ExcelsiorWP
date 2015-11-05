<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Client_Rest
 * @subpackage Client_Rest/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Client_Rest
 * @subpackage Client_Rest/includes
 * @author     Your Name <email@example.com>
 */
class Client_Rest {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Client_Rest_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $settings = array();
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'client-rest';
		$this->version = '1.0.0';

		$this->settings = array(
			'option_group' => 'reading',
			'option_name' => 'client_rest_dropbox_api_keys'
		);

		$this->load_dependencies();
		
		$this->set_locale();
		
		$this->register_cpt();
		
		$this->define_admin_hooks();
		
		$this->define_settings_callbacks();
		
		$this->define_public_hooks();
		
		$this->register_widgets();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Client_Rest_Loader. Orchestrates the hooks of the plugin.
	 * - Client_Rest_i18n. Defines internationalization functionality.
	 * - Client_Rest_Admin. Defines all hooks for the dashboard.
	 * - Client_Rest_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-client-rest-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-client-rest-i18n.php';
    
    /**
		 * The class that defines some helper functions to abstract various WP  
		 * functions.
		 */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-client-rest-helper.php';
		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-client-rest-admin.php';

		/**
		 * The class responsible for defining custom post types.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-client-rest-cpt.php';
		/**
		 * The class responsible for defining settings section sanitize callbacks.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-client-rest-settings-callbacks.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-client-rest-public.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-client-rest-widget.php';

		$this->loader = new Client_Rest_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Client_Rest_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Client_Rest_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	
	/**
	 * Register custom post type hooks
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_cpt() {
  	
  	$plugin_admin_cpt = new Client_Rest_Admin_CPT( $this->get_plugin_name(), $this->get_version() );

  	$this->loader->add_action( 'init', $plugin_admin_cpt, 'register_cpt_event' );
  }
  
  private function define_settings_callbacks() {
    
    $plugin_admin_cbs = new Client_Rest_Admin_Settings_Callbacks( $this->get_plugin_name(), $this->get_version() );
    
    $this->loader->add_action( 'admin_init', $plugin_admin_cbs, 'crest_ga_sanitize_callback' );  
  }
  
	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Client_Rest_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		$this->loader->add_action( 'wp_ajax_crest_get_streams', $plugin_admin, 'crest_get_streams' );
		
		// register settings
		$this->loader->add_action( 'admin_init', $plugin_admin, 'define_register_settings' );
		// register settings for GA page
		$this->loader->add_action( 'admin_init', $plugin_admin, 'define_ga_settings' );
		// add options page
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'define_settings_page' );
		// add main plugin page
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'define_top_level_menu' );
		// add submenu page for salts
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'define_sub_level_menu' );
		// add submenu page for analytics
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'define_sub_ga_menu' );
		// add universal widget page
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'define_universal_widget_page' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Client_Rest_Public( $this->get_plugin_name(), $this->get_version() );
    
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'crest_ga_display' ); 
		// Enqueue and localize ajax_url
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_action( 'wp_ajax_nopriv_crest_get_page', $plugin_public, 'crest_get_page' );
    $this->loader->add_action( 'wp_ajax_crest_get_page', $plugin_public, 'crest_get_page' );
    
    $this->loader->add_action( 'wp_ajax_nopriv_crest_get_transient_data', $plugin_public, 'crest_get_transient_data' );
    $this->loader->add_action( 'wp_ajax_crest_get_transient_data', $plugin_public, 'crest_get_transient_data' );
    
    $this->loader->add_action( 'wp_ajax_nopriv_crest_get_stream_detail', $plugin_public, 'crest_get_stream_detail' );
    $this->loader->add_action( 'wp_ajax_crest_get_stream_detail', $plugin_public, 'crest_get_stream_detail' );
	}
	
	private function register_widgets() {
  	
  	$plugin_public = new Client_Rest_Public( $this->get_plugin_name(), $this->get_version() );
  	
  	add_action( 'widgets_init',  create_function('', 'register_widget("Client_Rest_Widget");') );
  	//add_action('widgets_init', create_function('', 'return register_widget("example_widget");'));
  }
  
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Client_Rest_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
