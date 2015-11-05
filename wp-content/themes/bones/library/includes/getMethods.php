<?php
require_once 'classes/class.DB_Mysqli.php';
require_once 'classes/class.API.php';
require_once 'config.php';

$method = trim( $_GET['method'] );
$API->setMethod( $method );
$API->makeCurlRequest();
$json = $API->getResultJson();

echo $json;