<?php
include 'config.php'; //isere_osm_myisam`
ini_set('display_errors',1);
ini_set('display_startup_errors',1);



$direction= $_GET['direction'];

$route_id= $_GET['route_id'];
$date = $_GET['date'];
//$date = '2015-12-01';


$sql = "SELECT t.trip_id, MIN(st.departure_time) as depart, MAX(st.departure_time) as arrive, count(*) as nb_stop
FROM stop_times st
JOIN trips t ON t.trip_id = st.trip_id
JOIN (SELECT * FROM calendar_dates WHERE date = :date) cal ON cal.service_id = t.service_id 
WHERE t.route_id = :route_id AND t.direction_id = :direction
GROUP BY t.trip_id
ORDER by  MIN(st.departure_time),MAX(st.departure_time)"; // sans les trams

$req = $db->prepare($sql);

// on execute la requete avec les paramétres que l'on défini
$req->execute(array('direction' => $direction ,
                   'route_id'=> $route_id,
                   'date' => $date));
$result = $req->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);

?>