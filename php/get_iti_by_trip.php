<?php
include 'config.php'; //isere_osm_myisam`
ini_set('display_errors',1);
ini_set('display_startup_errors',1);

$trip_id= $_GET['trip_id'];


$sql = "SELECT string_agg('loc='||s_order.lat||','||s_order.lng,'&') as osrm
FROM(
SELECT  s.stop_lat as lat, s.stop_lon as lng,st.stop_sequence
FROM stop_times st
JOIN stops s ON s.stop_id = st.stop_id
WHERE st.trip_id = :trip_id
ORDER BY st.stop_sequence) s_order"; 

$req = $db->prepare($sql);

// on execute la requete avec les paramétres que l'on défini
$req->execute(array('trip_id' => $trip_id));
$result = $req->fetchAll(PDO::FETCH_ASSOC);
$str_osrm = $result[0]['osrm'];

$url = $osm_url . $str_osrm; 

$geom_encoded6 = json_decode (file_get_contents($url),true)['route_geometry'] ;
echo $geom_encoded6;

?>