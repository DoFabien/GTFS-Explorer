<?php
$osm_url = 'http://localhost:5005/';
$db_name = 'gis';
$userPG = 'postgres';
$passwordPG = '';
$host = 'localhost';
$schema = 'gtfs';

try {
  $db = new PDO("pgsql:host=$host;dbname=$db_name", $userPG, $passwordPG);

}
catch(PDOException $e) {
  $db = null;
  echo 'ERREUR DB: ' . $e->getMessage();
}
?>