<?php  
/****
  * Database connection class
  * 
  * Setup database connection and run a simple query
  * to ensure the connection is valid
  *
  * In this case, we are using this class as an
  * abstraction layer on top of Wordpress.
*/
namespace Includes\Classes;

class DB_Wordpress {
  /**
	 * Store a pointer to the Wordpress $wpdb global
	 *
	 * @var	object
	 */
  private $wpdb;
  /**
	 * WP DB Connection flag
	 *
	 * @var bool
	 */
  private $wp_is_connected;
  /**
    * Result type from WP DB query
    *
    * @var const
  */
  private $wp_result_type;
  /**
    * Result type options
    *
    * @var array
  */
  protected $wp_result_types = array(
    'assoc' => ARRAY_A,
    'numeric' => ARRAY_N,
    'object' => OBJECT
  );
  
  /**
    * Class constructor
    *
    * Takes the $wpdb global as an argument so we can
    * abstract the WPDB database. Check if connection
    * is valid and throw an exception if not valid
    *
    * @var object by reference
  */
  public function __construct(&$wpdb) {
    $this->wpdb = $wpdb;
    $this->check_wp_connect();
    $this->warn_connect();
  }
  
  /**
    * Set DB status
    *
    * @var void
  */
  private function check_wp_connect() {
    $count = $this->wpdb->get_var( "SELECT COUNT(*) FROM {$this->wpdb->users}" );
    if ($count) {
      $this->wp_is_connected = TRUE;
    } else {
      $this->wp_is_connected = FALSE;
    }
  }
  
  /**
    * Check DB connection, throw an exception if 
    * not connected
  */
  private function warn_connect() {
    if ( !$this->wp_is_connected ) {
      //throw new Exception("No WP DB connection");
      // log message goes here
    }
  }
  
  public function wpInsert($columns, $values) {
    
  }
  
  public function setWPInsertQuery($columns, $values) {
    $sql = 'INSERT INTO table ';
    $wp_columns = array_values($columns);
    $wp_values = array_values($values);
    
    $sql .= '(' . implode(",", $wp_columns) . ')';
    
    $sql .= ' VALUES ';
    $sql .= '(' . implode(",", $wp_values) . ')';
    print '<pre>';var_dump($sql);print '</pre>';
  
  }
  
  public function setWPSelect($select, $table, $uqid = NULL, $where = NULL, $like = NULL) {
    $selectors = '';
    $sql = 'SELECT ';
    
    if (is_array($select)) {
      $selectors = implode(",", $select);
    } else {
      $selectors = $select;
    }
    
    $sql .= $selectors . ' FROM `' . $table . '` ';
    
    if ($uqid) {
      $id = addslashes($uqid);
      $where_to =  addslashes($where);
      $sql .= 'WHERE `' . $id . '` ';
      if ($like) {
        $sql .= "LIKE '%{$where_to}%'";
      } else {
        $sql .= "= '$where_to'";
      }
    }
    return $sql;  
  }
  
  
  
  public function setResultType($type) {
    if ( !in_array($type, $this->wp_result_types) ) {
      // log message goes here
    } else {
      echo $this->wp_result_types[$type];
      $this->wp_result_type = $type;
    }
  }
  
  public function getResultType() {
    return $this->wp_result_type;
  }
}