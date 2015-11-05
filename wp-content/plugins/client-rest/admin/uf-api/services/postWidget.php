<?php

require '../classes/StreamRepository.php';

header( 'Content-type: application/json' );
//header( 'Content-type: application/x-www-form-urlencoded' );
//var_dump(json_encode($_POST['name']));
$data = file_get_contents( "php://input");
$decodedData = json_decode( $data );
$wid = $decodedData->wid;
$stitle = $decodedData->stitle;
$fSid = $decodedData->fSid;
$wtitle = $decodedData->wtitle;
$num_cards = $decodedData->num_cards;
$link_text = $decodedData->link_text;
$purl = $decodedData->purl;
$sharer = $decodedData->sharer;
$textnotes = $decodedData->textnotes;
$widget_data = $decodedData->data;

//var_dump($stream_data);

echo ")]}'\n";

if ( isset( $wid ) && isset( $fSid ) && isset( $wtitle ) ) {
  StreamRepository::addWidget( $wid, $stitle, $fSid, $wtitle, $num_cards, $link_text, $purl, $sharer, $textnotes, $widget_data );
  echo json_encode( true );
}
