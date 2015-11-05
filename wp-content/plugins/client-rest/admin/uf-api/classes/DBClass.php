<?php
class DBClass {

  // PDO driver name, followed by a colon, followed by the PDO driver-specific connection syntax.
  private static $DSN = 'mysql:dbname=db149378_excelsior;host=internal-db.s149378.gridserver.com';
  private static $DB_USERNAME = 'db149378_uf';
  private static $DB_PASSWORD = 'C3l$i0hF03mzFgssw?';

  private static $db = null;

  protected static function connect() {
    try {
      self::$db = new PDO( self::$DSN, self::$DB_USERNAME, self::$DB_PASSWORD );
    } catch( PDOException $e) {
      echo 'Connection failed: ' . $e->getMessage();
    }
  }

  public static function execute( $sql, $values = array() ) {
    if ( self::$db === null ) {
      self::connect();
    }
    //print '<pre>';var_dump( $sql );print '</pre>';
    // prepare method returns a PDOStatement class object
    $statement = self::$db->prepare( $sql );
    $statement_return = $statement->execute( $values );
    $statement_catch = $statement->errorInfo();
    //print '<pre>';var_dump( $statement_return );print '</pre>';
    //if ( !$statement_return ) {
      //print '<pre>';var_dump( $statement_catch );print '</pre>';
    //}
    return $statement;
  }

  public static function query( $sql, $values = array() ) {
    $statement = self::execute( $sql, $values );
    return $statement->fetchAll( PDO::FETCH_CLASS );
  }
}
