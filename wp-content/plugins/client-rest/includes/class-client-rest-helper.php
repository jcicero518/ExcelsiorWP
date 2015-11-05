<?php
/**
 * Helper class
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Client_Rest_Helper
 * @subpackage Client_Rest/includes
 */

final class Client_Rest_Helper {
  
  public function __construct() {
      
  }
  
  public static function settings_error( $setting, $code, $message, $type = 'error' ) {
     add_settings_error( $setting, $code, $message, $type );
  }
  
}