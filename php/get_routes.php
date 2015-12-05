<?php
include 'config.php'; //isere_osm_myisam`
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
$exception_route_type= $_GET['exception_route_type'];
//$exception_route_type = '0';
$sql = "SELECT * FROM routes r WHERE route_type not in ($exception_route_type)"; 

$req = $db->prepare($sql);

// on execute la requete avec les paramétres que l'on défini
$req->execute();
$result = $req->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);

?>