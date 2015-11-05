<?php

require_once 'DBHelper.php';
require_once 'Stream.php';
require_once 'Widget.php';

class StreamRepository {

  private static $streams = array();

  public static function init() {
    DBHelper::resetDB();
    /*DBHelper::addStream(
      new Stream( 'Test Static Stream', '100000', array(
        'HubItems->HubItem->name'
      )));*/
  }

  public static function getStreams() {
    return DBHelper::getStreams();
  }

  public static function getWidgets() {
    return DBHelper::getWidgets();
  }

  public static function checkWidgetsFor( $field, $fval ) {
    return DBHelper::checkWidgetsFor( $field, $fval );
  }

  public static function getStreamItemData( $stream_id ) {
    //return DBHelper::getStates( new Country( '', $countryCode ) );
  }

  public static function addStream( $name, $stream_id, $stream_data ) {
    return DBHelper::addStream( new Stream( $name, $stream_id, $stream_data ) );
  }

  public static function addWidget( $wid, $stitle, $fSid, $wtitle, $num_cards, $link_text, $purl, $sharer, $textnotes, $data ) {
    return DBHelper::addWidget( new Widget( $wid, $stitle, $fSid, $wtitle, $num_cards, $link_text, $purl, $sharer, $textnotes, $data ) );
  }

}
 ?>
