<?php
namespace Includes\Classes;

class DB_Mysqli {
  
    /**
     * The name of the database host
     * @access private
     * @var string
     */
    private $db_host = '';

    /**
     * The name of the database
     * @access private
     * @var string
     */
    private $db_name = '';

    /**
     * The name of the database user
     * @access private
     * @var string
     */
    private $db_user = '';

    /**
     * The database user's password
     * @access private
     * @var string
     */
    private $db_pass = '';
    
    private $struct_args = array();
    
    protected $resource_conn;
    
    public function __construct( $db_host, $db_user, $db_pass, $db_name ) {
      $this->struct_args = func_get_args();
      $this->db_connection();
    }
    
    private function db_connection() {
      $db_args = $this->struct_args;
      //$this->resource_conn = mysqli_connect( $db_args[0], $db_args[1], $db_args[2], $db_args[3] );
    }
  }