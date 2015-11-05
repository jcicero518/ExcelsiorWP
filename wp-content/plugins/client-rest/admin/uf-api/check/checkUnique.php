<?php

require '../classes/StreamRepository.php';
// We're expecting JSON from this service, set the header accordingly
header( 'Content-type: application/json' );
// prevent JSON hijacking.. technically makes the JSON invalid but Angular strips it out
//echo ")]}'\n";

$field = $_GET['field'];
$fval = $_GET['fval'];

if ( isset( $field ) && isset( $fval ) && $fval != 'undefined' ) {
  $results = StreamRepository::checkWidgetsFor( $field, $fval );
  echo $results;
}
