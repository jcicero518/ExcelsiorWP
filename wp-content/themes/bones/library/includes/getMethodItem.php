<?php
require_once 'classes/class.DB_Mysqli.php';
require_once 'classes/class.API.php';
require_once 'config.php';

$method = trim( $_GET['method'] );
$itemId = trim( $_GET['itemId'] );

$API->setMethod( $method );
$API->setItemId( $itemId );
$API->makeCurlRequest();
$json = $API->getResultJson();

echo $json;