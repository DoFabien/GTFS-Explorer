<?php
include 'config.php'; //isere_osm_myisam`
ini_set('display_errors',1);
ini_set('display_startup_errors',1);



$sql = "SELECT DISTINCT date FROM calendar_dates ORDER BY date"; // sans les trams

$req = $db->prepare($sql);

// on execute la requete avec les paramétres que l'on défini
$req->execute();
$result = $req->fetchAll(PDO::FETCH_ASSOC);
$dates = array();
for ($i = 0; $i<count($result);$i++){
    array_push ($dates,$result[$i]['date']);
}
echo json_encode($dates);

?>






































