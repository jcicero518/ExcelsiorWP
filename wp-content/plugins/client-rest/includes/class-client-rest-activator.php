<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Client_Rest
 * @subpackage Client_Rest/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Client_Rest
 * @subpackage Client_Rest/includes
 * @author     Your Name <email@example.com>
 */
class Client_Rest_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
    
    flush_rewrite_rules();
    
	}

}
