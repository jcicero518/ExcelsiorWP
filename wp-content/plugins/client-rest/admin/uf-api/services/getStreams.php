<?php

require '../classes/StreamRepository.php';
// We're expecting JSON from this service, set the header accordingly
header( 'Content-type: application/json' );
// prevent JSON hijacking.. technically makes the JSON invalid but Angular strips it out
echo ")]}'\n";
// Retrieve array of streams from our static StreamRepository class
$streams = StreamRepository::getStreams();
echo json_encode( $streams );
