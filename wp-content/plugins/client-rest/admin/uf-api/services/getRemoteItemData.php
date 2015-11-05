<?php

require '../classes/StreamRepository.php';
require '../classes/UFAPI.php';

// We're expecting JSON from this service, set the header accordingly
header( 'Content-type: application/json' );
// prevent JSON hijacking.. technically makes the JSON invalid but Angular strips it out
echo ")]}'\n";

$API = new UFAPI();

$uf_method = '';
$uf_itemID = '';

if ( isset( $_GET['method']) && !is_array( $_GET['method']) ) {
  $uf_method = $_GET['method'];
  $API->setMethod( $uf_method );
}
if ( isset($_GET['itemid']) && !is_array( $_GET['itemid'] ) ) {
  $uf_itemID = $_GET['itemid'];
  $API->setItemID( $uf_itemID );
}

$API->makeCurlRequest();
$json = $API->getResultJson();
//$streams = StreamRepository::getStreams();
// parse the json into MySQL rows for DB - json-encode?
//StreamRepository::addStream( $name, $sid, $data );
//echo json_encode( $streams );
echo $json;
