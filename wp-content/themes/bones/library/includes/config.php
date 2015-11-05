<?php 
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '1234';
$DB_NAME = 'uberflip';

$mysqli = new \Includes\Classes\DB_Mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
global $API;
$API = new \Includes\Classes\API( $mysqli );
?>
