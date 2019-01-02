<?php
include 'config.php'; //isere_osm_myisam`
ini_set('display_errors',1);
ini_set('display_startup_errors',1);

$trip_id= $_GET['trip_id'];


$sql = "
SELECT string_agg(concat(s_order.lng, ',',s_order.lat),';') coords
FROM( 
	SELECT s.stop_lat as lat, s.stop_lon as lng,st.stop_sequence 
    FROM $schema.stop_times st 
    JOIN $schema.stops s ON s.stop_id = st.stop_id 
 WHERE st.trip_id = :trip_id 
ORDER BY st.stop_sequence) s_order"; 

$req = $db->prepare($sql);


// on execute la requete avec les paramétres que l'on défini
$req->execute(array('trip_id' => $trip_id));
$result = $req->fetchAll(PDO::FETCH_ASSOC);
/// route/v1/driving/13.388860,52.517037;13.397634,52.529407;13.428555,52.523219?overview=false
// $str_osrm = $result[0]['coords'];
$coords = $result[0]['coords'];

$url = $osm_url  . "route/v1/driving/$coords?overview=full&geometries=polyline6";

// $url = $osm_url . $str_osrm; 
// print_r($url);
// $url = 'http://localhost:5005/route/v1/driving/5.7105,45.19996;5.71174,45.19709;5.71351,45.19501?overview=full&geometries=polyline6';
$geom_encoded6 = json_decode (file_get_contents($url),true)['routes'][0]['geometry'] ;
echo $geom_encoded6;

?>