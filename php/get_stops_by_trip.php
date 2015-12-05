<?php
include 'config.php'; //isere_osm_myisam`
ini_set('display_errors',1);
ini_set('display_startup_errors',1);

//$trip_id= '573060';
$trip_id= $_GET['trip_id'];


$sql = "SELECT s.stop_id, s.stop_name, s.stop_lat as lat, s.stop_lon as lng, s.wheelchair_boarding, st.stop_sequence,st.arrival_time
FROM stop_times st
JOIN stops s ON s.stop_id = st.stop_id
WHERE st.trip_id = :trip_id
ORDER BY st.stop_sequence"; 

$req = $db->prepare($sql);

// on execute la requete avec les paramétres que l'on défini
$req->execute(array('trip_id' => $trip_id));
$result = $req->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);

?>