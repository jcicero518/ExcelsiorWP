<?php

require '../classes/StreamRepository.php';

header( 'Content-type: application/json' );
echo ")]}'\n";

//if ( isset( $_GET['name'] ) && is_string( $_GET['stream_id'] ) && isset( $_GET['countryCode'] ) && is_string( $_GET['countryCode'] ) ) {
if ( isset( $_GET['name']) && isset( $_GET['stream_id']) && isset($_GET['stream_data']) ) {
  StreamRepository::addStream( $_GET['name'], $_GET['stream_id'], $_GET['stream_data'] );
  echo json_encode( true );
}
