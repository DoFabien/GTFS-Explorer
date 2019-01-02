<?php
include 'config.php'; //isere_osm_myisam`
ini_set('display_errors',1);
ini_set('display_startup_errors',1);

//$trip_id= $_GET['trip_id'];

$trip_id = '578126';


$sql = "SELECT s.stop_id, s.stop_name, s.stop_lat, s.stop_lon, s.wheelchair_boarding, st.arrival_time, stop_sequence
FROM $schema.stops s
JOIN $schema.stop_times st ON st.stop_id = s.stop_id
JOIN $schema.trips t ON t.trip_id = st.trip_id
WHERE t.trip_id = :trip_id
ORDER BY stop_sequence";

$req = $db->prepare($sql);

// on execute la requete avec les paramétres que l'on défini
$req->execute(array(
    'trip_id'=>$trip_id
));
$result = $req->fetchAll(PDO::FETCH_ASSOC);

//echo json_encode($result);


function get_data($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}


$itis = array();
for($i=0; $i<count($result) -1;$i++){ 
    
    $param_str = "loc=". $result[$i]['stop_lat'] .','.$result[$i]['stop_lon'] .'&loc='.$result[$i+1]['stop_lat'] .','.$result[$i+1]['stop_lon'];
    $url = 'http://dogeo.fr:5005/viaroute?' . $param_str; //loc=45.18452,5.72094&loc=45.36348,5.58775';
    //echo $url;
    $req = get_data($url);
   // echo $req;
    $decoded = json_decode($req);

    $geom = null;
    if (isset($decoded->route_geometry)){
        $geom = $decoded->route_geometry;
    }
    array_push($itis,$geom);
}

echo json_encode(array($result,$itis));

?>