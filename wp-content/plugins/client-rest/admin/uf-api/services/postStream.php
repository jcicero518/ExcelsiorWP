<?php

require '../classes/StreamRepository.php';

header( 'Content-type: application/json' );
//header( 'Content-type: application/x-www-form-urlencoded' );
//var_dump(json_encode($_POST['name']));
$data = file_get_contents( "php://input");
$decodedData = json_decode( $data );
$name = $decodedData->name;
$stream_id = $decodedData->stream_id;
$stream_data = $decodedData->stream_data;

//var_dump($stream_data);

echo ")]}'\n";

if ( isset( $name ) && isset( $stream_id ) && isset( $stream_data ) ) {
  StreamRepository::addStream( $name, $stream_id, $stream_data );
  echo json_encode( true );
}
