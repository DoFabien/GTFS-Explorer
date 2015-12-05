<?php
$osm_url = 'http://localhost:5005/viaroute?';
$db_name = 'gtfs';
$userPG = 'postgres';
$passwordPG = '';
$host = 'localhost';

try {
  $db = new PDO("pgsql:host=$host;dbname=$db_name", $userPG, $passwordPG);

}
catch(PDOException $e) {
  $db = null;
  echo 'ERREUR DB: ' . $e->getMessage();
}
?>