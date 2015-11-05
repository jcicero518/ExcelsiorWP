<?php
require_once 'DBClass.php';
require_once 'Stream.php';
require_once 'Widget.php';
//require_once 'State.php';

class DBHelper {

  public static function resetDB() {
    DBClass::execute( 'DROP TABLE `uf_widgets`' );
    //DBClass::execute( 'DROP TABLE `uf_streams`' );
    //DBClass::execute( 'DROP TABLE states' );
    /*DBClass::execute( 'CREATE TABLE `countries` (
      `name` VARCHAR(50),
      `code` VARCHAR(10) PRIMARY KEY
    )');*/
    DBClass::execute( 'CREATE TABLE `uf_streams` (
      `id`    INT(10) PRIMARY KEY AUTO_INCREMENT,
      `sid`   INT(10),
      `name`  VARCHAR(128),
      `count` INT(10),
      `data` TEXT
    )');
    DBClass::execute( 'CREATE TABLE `uf_widgets` (
      `wpid` INT(10) PRIMARY KEY AUTO_INCREMENT,
      `wid` INT(20),
      `stitle` VARCHAR(128),
      `fSid` INT(20),
      `wtitle` VARCHAR(128),
      `num_cards` INT(10),
      `link_text` VARCHAR(64),
      `sharer` VARCHAR(12),
      `textnotes` TEXT,
      `purl` VARCHAR(256),
      `data` TEXT
    )');
    /*DBClass::execute( 'CREATE TABLE `states` (
      `name` VARCHAR(50),
      `code` VARCHAR(10)
    )');*/
  }

  public static function getStreams() {
    $streams = array();

    $db_streams = DBClass::query( 'SELECT * FROM uf_streams' );
    foreach ( $db_streams as $db_stream ) {
      $stream = new Stream( $db_stream->name, $db_stream->sid, $db_stream->data );
      array_push( $streams, $stream );
    }

    return $streams;
  }

  public static function getWidgets() {
    $widgets = array();

    $db_widgets = DBClass::query( 'SELECT * FROM `uf_widgets`' );
    foreach ( $db_widgets as $db_widget ) {
      $widget = new Widget(
        $db_widget->wid, $db_widget->stitle,
        $db_widget->fSid, $db_widget->wtitle,
        $db_widget->num_cards, $db_widget->link_text,
        $db_widget->purl, $db_widget->sharer,
        $db_widget->textnotes, $db_widget->data );
        array_push( $widgets, $widget );
    }

    return $widgets;
  }

  public static function checkWidgetsFor( $field, $fval ) {
    $db_field_name = '';
    $widget_match = 0;

    switch ( $field ) {
      case 'uf-title':
        $db_field_name = 'title';
        break;
      default:
        $db_field_name = 'title';
    }

    $db_widgets = DBClass::query( 'SELECT COUNT(*) as count FROM `uf_widgets` WHERE `' . $db_field_name . '`  = ?',
      array( $fval ) );

    if ( count( $db_widgets ) ) {
      $widget_match = $db_widgets[0]->count;
    } else {
      return 0;
    }
    /*foreach ( $db_widgets as $db_widget ) {
      $widget_match = $db_widget->count;
    }*/

    return $widget_match;
  }

  public static function addStream( Stream $stream ) {
    $stream_data = json_encode( $stream->stream_data );

    return DBClass::execute(
      'INSERT INTO uf_streams (name, sid, data) VALUES (?, ?, ?)',
      array( $stream->name, $stream->stream_id, $stream_data ));
  }

  public static function addWidget( Widget $widget ) {
    $widget_data = json_encode( $widget->widget_data );

    return DBClass::execute(
      'INSERT INTO uf_widgets (wid, stitle, fSid, wtitle, num_cards, link_text, purl, sharer, textnotes, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
      array( $widget->wid, $widget->stitle, $widget->fSid, $widget->wtitle, $widget->num_cards, $widget->link_text, $widget->purl, $widget->sharer, $widget->textnotes, $widget_data ));
  }

}
