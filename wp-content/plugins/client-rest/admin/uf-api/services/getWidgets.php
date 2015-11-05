<?php
require '../classes/StreamRepository.php';
require '../classes/UFAPI.php';

// We're expecting JSON from this service, set the header accordingly
header( 'Content-type: application/json' );
// prevent JSON hijacking.. technically makes the JSON invalid but Angular strips it out
echo ")]}'\n";

$widgets = StreamRepository::getWidgets();
echo json_encode( $widgets );
