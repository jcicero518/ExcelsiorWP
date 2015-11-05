<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Client_Rest
 * @subpackage Client_Rest/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Client_Rest
 * @subpackage Client_Rest/admin
 * @author     Your Name <email@example.com>
 */
class Client_Rest_Admin {

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
	 * The settings prefix.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $prefix    The settings prefix.
	 */
	private $settings_prefix = 'crest_sett';
  private $plugin_prefix = 'crest';
  
	protected $options_defaults;

	protected $options_settings;
	
	private $helper;
	
	protected $salt_settings = array();
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
		
		$this->helper = @Client_Rest_Helper;
		
		$this->options_defaults = array(
			'apiKey'    => 'abcdefghijklmnop',
			'apiSecret' => 'abcdefghijklmnop'
		);
		
		$this->salt_settings = array(
  		'url' => 'https://api.wordpress.org/secret-key/1.1/salt/'
    );
    
    $this->options_ga_settings = array(
      'settings' => 'crest_ga_settings',
      'section' => 'crest_ga_section',
      'page' => 'crest_ga_page',
      //'crest_ga_tracking_code' => ''
    );
    
		$this->options_settings = array(
			'settings_prefix' => 'crest_sett',
			'section' => 'clientrest_settings_section',
			'option_name' => 'clientrest_settings_dropbox',
			'page' => 'crest_options',
			'settings_page' => array(
  			'dbx' => 'crest_options'
  		),
			'sanitize_cback' => array(&$this, 'crest_stz')
		);

		$this->add_options();
	}
	
	private function add_options() {
		update_option( 'client_rest_options', $this->options_settings );
		//update_option( 'crest_ga_options', $this->options_ga_settings );
	}

	/**
	  * The register_setting function defines the actual option the 
	  * setting fields will be stored in.
	*/
	public function define_register_settings() {
		/**
		 * @params $settings_section 			- whitelisted option key name (general, reading, writing)
		 *		   $option_name      			- option name to sanitize and save (for get_option, update_option)
		 * 		   (optional) $callback         - callback function to sanitize options value
		*/
		register_setting(
  		'crest_options',
		  'clientrest_settings_dropbox'
    );
		
		// @params $id, $title, $callback, $page
		add_settings_section( 
			'clientrest_settings_section', 
			__( 'Dropbox API Settings' ), 
			array(&$this, 'clientrest_dropbox_settings_section_callback'),
			$this->options_settings['settings_page']['dbx']	
		);
    
    // @params $id, $title, $callback, $page, $section, $args = array()
		add_settings_field(
			'clientrest_text_field_0',
			__( 'Dropbox API Key' ),
			array(&$this, 'clientrest_dropbox_settings_field_callback_key'),
			$this->options_settings['settings_page']['dbx'],
			'clientrest_settings_section'
		);
    
    // @params $id, $title, $callback, $page, $section, $args = array()
		add_settings_field(
			'clientrest_text_field_1',
			__( 'Dropbox API Secret' ),
			array(&$this, 'clientrest_dropbox_settings_field_callback_secret'),
			$this->options_settings['settings_page']['dbx'],
			'clientrest_settings_section'
		);
	
		
	}
	
	public function define_ga_settings() {
  	register_setting( 'crest_ga_section', 'crest_ga_options', array( &$this, 'crest_ga_section_cb' ) );
    
    add_settings_section(
      'crest_ga_settings_section',
      __( 'GA Section' ),
      array( &$this, 'crest_ga_settings_callback' ),
      'crest_ga_section'
    );
    
    add_settings_field(
      'crest_ga_tracking_code',
      __( 'Tracking Code' ),
      array( &$this, 'crest_ga_track_field_callback' ),
      'crest_ga_section',
      'crest_ga_settings_section'
    );
    
    register_setting( 'crest_uberflip_section', 'crest_uberflip_options' );
    
    add_settings_section(
	    'crest_uberflip_settings_section',
	    __( 'Carousel Settings' ),
	    array( &$this, 'crest_uberflip_settings_callback' ),
	    'crest_uberflip_section'
	  );
	  
	  add_settings_field(
		  'crest_uberflip_carousel_num',
		  __( 'Number of Items' ),
		  array( &$this, 'crest_uberflip_carousel_num_callback' ),
		  'crest_uberflip_section',
		  'crest_uberflip_settings_section'
		);
		// wp_dropdown_pages( array( 'name' => 'page_on_front', 'echo' => 0, 'show_option_none' => __( '&mdash; Select &mdash;' ), 'option_none_value' => '0', 'selected' => get_option( 'page_on_front' ) ) ) );
		
		add_settings_field(
			'crest_uberflip_carousel_stream',
			__( 'Select Stream' ),
			array( &$this, 'crest_uberflip_carousel_stream_callback' ),
			'crest_uberflip_section',
			'crest_uberflip_settings_section'
		);
  
  }
  
  public function crest_uberflip_settings_callback() {
	  $options = get_option( 'crest_uberflip_options' );
	  $num = $options['crest_uberflip_carousel_num'];
	  ?>
	  
	<?php
	}
	
	public function crest_uberflip_carousel_stream_callback() {
		?>
		<div id="streamContainer"></div>
		<select name="crest_uberflip_options[crest_uberflip_carousel_stream]" id="crest_stream_list">
			<option></option>
		</select>
	<?php
	}
	
	public function crest_uberflip_carousel_num_callback() {
		$options = get_option( 'crest_uberflip_options' );
	  $num = $options['crest_uberflip_carousel_num'];
	  ?>
	  <input name="crest_uberflip_options[crest_uberflip_carousel_num]" type="number" step="1" min="1" id="crest_uberflip_carousel_num" value="<?php echo $num; ?>" class="small-text" />
	<?php
  }
  
  public function clientrest_uberflip_page_callback() {
	  ?>
	  <div class="wrap">
      <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

				<form method="post" action="options.php">
	        <?php 
	        settings_fields( 'crest_uberflip_section' );
	        do_settings_sections( 'crest_uberflip_section' );
	        settings_errors();
	        submit_button();
	        ?>
	      </form>
        				
	      <h2> Hub Streams - Displaying first custom stream </h2>
        <?php
        
        $rbody = '';
        
        $endPoint = 'https://api.uberflip.com';
        $hubID = '57003';
        $method = 'GetHubStreams';
        
        $api = array(
          'APIKey' => 'NGZjZGQzZGFjMWQ1ODAwNzU1N2ZiZjlhYzFjZWZkMGI=',
          'Signature' => 'MWI2MjUyYTFhMTY1ODYzMmQzZmJhMTMzNjY4ZWQzMmJlN2FhYjExYQ==',
          'Method' => $method,
          'Version' => '0.1',
          'ResponseType' => 'JSON'
        );
        
        // Custom stream needs hubid
        $api['HubId'] = $hubID;
        $api['Limit'] = 1;
        
        
        $params = http_build_query( $api );  
        //var_dump( $params );
        $rget = wp_remote_get( $endPoint . '?' . $params );
        $rbody = json_decode( $rget['body'] );
        print '<pre>';
        print_r( $rbody );
        print '</pre>';
        ?>
        
        <h2> Identified custom stream ID 203436</h2>
        <h3> Displaying hub items for 203436 </h3>
        <?php
        // Use optional HubStreamID
        $api['HubStreamId'] = '203436';
        $api['Limit'] = 22;
        $api['Method'] = 'GetHubItems';
        $params = http_build_query( $api );
        
        $rget2 = wp_remote_get( $endPoint . '?' . $params );
        
        $rbody2 = json_decode( $rget2['body'] );
        print '<pre>';
        print_r( $rbody2 );
        print '</pre>';
        ?>
        
        <h2> Hub Item ID # 104146456 </h2>
        <h3> A Conversation with Dr. John Ebersole </h3>
        <?php
        $apiItemData = array(
          'APIKey' => 'NGZjZGQzZGFjMWQ1ODAwNzU1N2ZiZjlhYzFjZWZkMGI=',
          'Signature' => 'MWI2MjUyYTFhMTY1ODYzMmQzZmJhMTMzNjY4ZWQzMmJlN2FhYjExYQ==',
          'Method' => $method,
          'Version' => '0.1',
          'ResponseType' => 'JSON'
        );
        $apiItemData['Method'] = 'GetHubItemData';
        $apiItemData['ItemId'] = '104146456';
        $params3 = http_build_query( $apiItemData );
        
        $rget3 = wp_remote_get( $endPoint . '?' . $params3 );
        
        $rbody3 = json_decode( $rget3['body'] );
        print '<pre>';
        print_r( $rbody2 );
        print '</pre>';
        ?>
			</div>
	  
	
	<?php
	}
  
  public function crest_ga_settings_callback() {
    //echo __( 'GA Settings', 'client-rest' );
  }
  
  public function crest_ga_track_field_callback() {
    $options = get_option( 'crest_ga_options' );
    $code = $options['crest_ga_tracking_code'];
    ?>
    <input name="crest_ga_options[crest_ga_tracking_code]" id="crest_ga_options_code" type="text" value="<?php echo $options['crest_ga_tracking_code'] ?>" />
  <?php
  }
  
  /**
   * Validate input - sanitize callback function
   * @params array $values
  */
  public function crest_ga_section_cb( $values ) {
    
    $default_values = array(
      'crest_ga_tracking_code' => 'UA-XXXXXXX-XXX'
    );
    
    $error_flag = false;
    
    var_dump( $values );
    // this should be an associative array. If its
    // not, return some bogus values
    if ( !is_array( $values ) ) {
      return $default_values;
    }
    
    $output = array();
    
    // loop through default values and assign 
    // the default if input is empty
    foreach ( $default_values as $key => $value ) {
      if ( empty( $values[$key] ) ) {
        $output[$key] = $value;
        $error_flag = true;
      }
    
      if ( $error_flag ) {
        $message = __( 'Data cannot be empty', 'client-rest' );
        Client_Rest_Helper::settings_error( 'crest_ga_tracking_code', 'crest_ga_options_code', $message, 'error' );
      } else {
        $message = __( 'Settings updated', 'client-rest' );
        Client_Rest_Helper::settings_error( 'crest_ga_tracking_code', 'crest_ga_options_code', $message, 'update' );
      }
    }
    
    // loop through input values and
    // sanitize values before assigning
    foreach ( $values as $key => $value ) {
      if ( !empty( $values[$key] ) ) {
        $output[$key] = sanitize_text_field( $value );
      }
    }
    
    return $output;
  }
    	
	
	public function crest_stz() {
  	var_dump(func_get_args());
  }

	// ------------------------------------------------------------------
 	// Settings section callback function
 	// -- Renders output
 	// ------------------------------------------------------------------
	//
	public function clientrest_dropbox_settings_section_callback() {
		//echo __( 'Dropbox API Keys', 'client-rest' );
	}

	// ------------------------------------------------------------------
 	// Settings field callback function
 	// -- Renders output (http://wpsettingsapi.jeroensormani.com/)
 	// ------------------------------------------------------------------
	//
	public function clientrest_dropbox_settings_field_callback_key() {
		$options = get_option( 'clientrest_settings_dropbox');
		$apiKey = $options['clientrest_text_field_0'];
		?>
		<input name="clientrest_settings_dropbox[clientrest_text_field_0]" id="clientrest_settings_dropbox_api_key" type="text" value="<?php echo $apiKey; ?>" class="code" />
	<?php
	}

	public function clientrest_dropbox_settings_field_callback_secret() {
		$options = get_option( 'clientrest_settings_dropbox' );
		$apiSecret = $options['clientrest_text_field_1'];
		?>
		<input name="clientrest_settings_dropbox[clientrest_text_field_1]" id="clientrest_settings_dropbox_api_secret" type="text" value="<?php echo $apiSecret; ?>" class="code" />
	<?php
	}
  
  /**
   * Defines a new options page with add_options_page().
   *
  */
	public function define_settings_page() {
		/**
     * add_options_page()
     * @params $page_title, $menu_title, $capability, $menu_slug, $function
     *
    */
		add_options_page( 
		  __( 'Client REST Setup' ), 
		  __( 'Client REST Dropbox' ), 
		  'manage_options', 
		  'cres_opt_page', array( &$this, 'clientrest_opt_page_callback' )
    );
	}
  
	public function clientrest_opt_page_callback() {
		?>
		<div class="wrap">
  		
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			
			<div id="poststuff">
        <div id="post-body">
          <div id="post-body-content">
            <form method="post" action="options.php">
              <?php 
              settings_fields( $this->options_settings['page'] );
              do_settings_sections( $this->options_settings['page'] );
              settings_errors();
              submit_button();
              ?>
            </form>
          </div>
        </div>
		</div>
	<?php
	}
	
	public function print_current_hook() {
  	echo '<pre>';print_r( current_filter() );echo '</pre>';
  }
	
	public function clientrest_ga_page_callback() {
    ?>
    <div class="wrap">
      
      <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
      <div id="poststuff">
        <div id="post-body">
          <div id="post-body-content">
            <?php
              add_action('any', array( &$this, 'print_current_hook') );
            ?>
            <form method="post" action="options.php">
              <?php 
              settings_fields( 'crest_ga_section' );
              do_settings_sections( 'crest_ga_section' );
              settings_errors();
              submit_button();
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php
  }

	
	/**
	 * Define top menu page for plugin with add_menu_page().
	 *
	 * @since    1.0.0
	 */
	public function define_top_level_menu() {
  	/**
     * add_menu_page()
     * @params $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position
    */
  	add_menu_page(
    	__( 'Client REST' ),
    	__( 'Client REST' ),
    	'manage_options',
    	'cres_top_menu_page',
    	array( &$this, 'clientrest_top_menu_page_callback' )
    );
  	
  }
  
  /**
	 * Define sub menu page for plugin with add_submenu_page().
	 *
	 * @since    1.0.0
	 */
  public function define_sub_level_menu() {
    /**
     * add_submenu_page()
     * @params $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function
    */
    add_submenu_page(
      'cres_top_menu_page',
      __( 'Salter API' ),
      __( 'Salter API' ),
      'manage_options',
      'cres_salt_page',
      array( &$this, 'clientrest_salt_page_callback' )
    );
  
  }
  
  public function define_sub_ga_menu() {
    /**
     * add_submenu_page()
     * @params $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function
    */
    add_submenu_page(
      'cres_top_menu_page',
      __( 'Analytics' ),
      __( 'Analytics' ),
      'manage_options',
      'cres_ga_page',
      array( &$this, 'clientrest_ga_page_callback' )
    );
    
    add_submenu_page(
      'cres_top_menu_page',
      __( 'Uberflip' ),
      __( 'Uberflip' ),
      'manage_options',
      'cres_uberflip_page',
      array( &$this, 'clientrest_uberflip_page_callback' )
    );
  
  }
  
  public function clientrest_top_menu_page_callback() {
   ?>
   <div class="wrap">
      
      <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
      <div id="poststuff">
        <div id="post-body">
          <div id="post-body-content">
            <?php
            global $wpdb;
            $db['prefix'] = $wpdb->prefix;
            $db['tables'] = $wpdb->tables;
            // UPDATE crpc_posts SET post_content = REPLACE(post_content, 'cdrpc.org.s76633.gridserver.com', 'cdrpc.org')
            // UPDATE crpc_postmeta SET meta_value = REPLACE(meta_value, 'cdrpc.org.s76633.gridserver.com', 'cdrpc.org')
            //print '<pre>';print_r($wpdb);print '</pre>';
            // update [table_name] set [field_name] = replace([field_name],'[string_to_find]','[string_to_replace]');
            global $wp_filesystem;
            print '<pre>';print_r($wp_filesystem);print '</pre>';
            ?>
          </div>
        </div>
      </div>
   </div>
  <?php 
  }
  
  public function clientrest_salt_page_callback() {
    ?>
   <div class="wrap">
      
      <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
      <div id="poststuff">
        <div id="post-body">
          <div id="post-body-content">
            <?php
            $args = array(
              'url' => 'https://api.wordpress.org/secret-key/1.1/salt/'
            );
            $rget = wp_remote_get( $args['url'] );
            $rbody = htmlentities($rget['body']);
            print '<pre>';
            print $rbody;
            print '</pre>';
            ?>
          </div>
        </div>
      </div>
   </div>
  <?php 
  }
  
    
	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Client_Rest_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Client_Rest_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/client-rest-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Client_Rest_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Client_Rest_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/client-rest-admin.js', array( 'jquery' ), $this->version, false );
		
		// Create a nonce first to ensure this can be validated as a legitimate request
    $title_nonce = wp_create_nonce( 'cr_ajax_obj_wp_nonce' );
    
    wp_localize_script( $this->plugin_name, 'cr_ajax_obj', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'nonce' => $title_nonce
      )
    );

	}
	
	public function crest_get_streams() {
		check_ajax_referer( 'cr_ajax_obj_wp_nonce' );
		//delete_transient( 'uber_hubstream_data' );
		//wp_die();
		//var_dump( func_get_args() );
		var_dump( get_transient( 'uber_hubstream_data' ) );
		if ( false !== get_transient( 'uber_hubstream_data' ) ) {
			wp_die( 'Transient already exists' );
		}
		$rbody = '';
        
    $endPoint = 'https://api.uberflip.com';
    $hubID = '57003';
    $method = 'GetHubStreams';
    
    $api = array(
      'APIKey' => 'NGZjZGQzZGFjMWQ1ODAwNzU1N2ZiZjlhYzFjZWZkMGI=',
      'Signature' => 'MWI2MjUyYTFhMTY1ODYzMmQzZmJhMTMzNjY4ZWQzMmJlN2FhYjExYQ==',
      'Method' => $method,
      'Version' => '0.1',
      'ResponseType' => 'JSON'
    );
    
    // Custom stream needs hubid
    $api['HubId'] = $hubID;
    //$api['Limit'] = 1;
    
    
    $params = http_build_query( $api );  
    $rget = wp_remote_get( $endPoint . '?' . $params );
    $rbody = json_decode( $rget['body'] );
    print '<pre>';
    print_r( $rbody );
    print '</pre>';
    
    $transient_stream = array();
    
    for ($i = 0; $i < count( $rbody ); $i++ ) {
	    $transient_stream[$i]['id'] = $rbody[$i]->HubStream->id;
	    $transient_stream[$i]['title'] = $rbody[$i]->HubStream->title;
	    $transient_stream[$i]['created'] = $rbody[$i]->HubStream->created;
	    $transient_stream[$i]['modified'] = $rbody[$i]->HubStream->modified;
	    $transient_stream[$i]['type'] = $rbody[$i]->HubStream->type;
	    $transient_stream[$i]['thumbnail_url'] = $rbody[$i]->HubStream->thumbnail_url;
	    $transient_stream[$i]['url'] = $rbody[$i]->HubStream->url;
	    $transient_stream[$i]['hidden'] = $rbody[$i]->HubStream->hidden;
	    $transient_stream[$i]['muted'] = $rbody[$i]->HubStream->muted;
	    $transient_stream[$i]['total_items'] = $rbody[$i]->HubStream->total_items;
	  }
    
    // Expires in 12 hours. 
    // set_site_transient() counterpart for multisite will always autoload / no expiration
    if ( false === get_transient( 'uber_hubstream_data' ) ) {
    	set_transient( 'uber_hubstream_data', $transient_stream, 12 * HOUR_IN_SECONDS );
    }

		
		wp_die( );
	}

}
