var app = angular.module('MainApp', ['ngMaterial']);

app.controller('MainCtrl', function($scope,$window,Fctory){

var exception_route_type = '-1'; //sans les trams => '0'. Pour ajouter une exeption => '0,3'
var osrm_itinaire = true; // calculer l'itineraires et l'afficher
    
    var map = $window.L.map('map',{ loadingControl: true}).setView([ 45.17840,5.72 ], 12); //
    var  FG_stops = L.featureGroup().addTo(map);
    var  FG_iti = L.featureGroup().addTo(map);

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(map);

    $scope.direction_selected = 0;
    $scope.route_selected = null;
    $scope.route_color =  "009930";

    $scope.dates = [];

    var dateStrToDate = function(date_str){
        var arr_date = date_str.split('-');
        return  new Date(arr_date[0], arr_date[1] - 1, arr_date[2]);
    };


    var dateToDateStr = function(date){
        var yyyy = date.getFullYear().toString();
        var mm = (date.getMonth()+1).toString();
        var dd  = date.getDate().toString();
        return  yyyy +'-'+ (mm[1]?mm:"0"+mm[0]) +'-'+ (dd[1]?dd:"0"+dd[0]);
    };

    $scope.filterDates = function(date) {
        return ($scope.dates.indexOf(dateToDateStr(date)) != -1) ? true : false;
    }

    Fctory.getDates(function(data){
        $scope.dates = data;
        $scope.min_date = dateStrToDate($scope.dates[0]);
        $scope.max_date = dateStrToDate($scope.dates[$scope.dates.length - 1 ]);
        $scope.date = ($scope.dates.indexOf(dateToDateStr(new Date())) != -1) ? new Date() : dateStrToDate($scope.dates[0]);

    });

    Fctory.getRoutes(exception_route_type,function(data){
        $scope.routes = data;
        $scope.route_selected = JSON.stringify($scope.routes[0]);
        $scope.$apply();
    });


    $scope.getTrip = function (trip){
        FG_stops.clearLayers();
        FG_iti.clearLayers();

        if (trip.selected == false){

        }

        if (trip.selected == true){
            for(var i =0; i<$scope.trips.length;i++){$scope.trips[i].selected = false};
            trip.selected = true;


            Fctory.getStopsByTrip(trip.trip_id, function(data){
                for (var i = 0; i< data.length; i++){
                    var marker_color = 'blue';
                    var marker_icon = '';
                    if (i== data.length -1) marker_color = 'red';
                    if (i== 0) marker_color = 'green';
                    if(data[i].wheelchair_boarding == 1) marker_icon = 'wheelchair';
                    var marker = L.marker([data[i].lat, data[i].lng], {icon:L.AwesomeMarkers.icon({ prefix:'map-icon',icon: marker_icon,markerColor:marker_color })});
                    marker.bindPopup( '<b>'+data[i].stop_name + '</b><br> ' 
                                     + data[i].arrival_time);
                    marker.addTo(FG_stops);
                }
                map.fitBounds(FG_stops.getBounds());
            });
            if (osrm_itinaire && JSON.parse($scope.route_selected).route_type != 0){ // != tram!
            Fctory.getItiByTrip(trip.trip_id,function(data){
                var  iti = new L.Polyline(L.PolylineUtil.decode(data, 6));
                iti.setStyle({color:'#'+$scope.route_color,weight:5, opacity:1});
                iti.addTo(FG_iti);
                //on ajoute les fleches
                decorator = L.polylineDecorator(iti, {
                    patterns: [
                        { offset: '16%', repeat: '100', symbol: L.Symbol.marker({rotate: true, markerOptions: {
                            icon: L.icon({
                                iconUrl: 'fleche.png',
                                iconAnchor: [8, 8]
                            })
                        }})}
                    ]
                }).addTo(FG_iti) ;
            });
        }
        }

    };

    $scope.$watchCollection('[direction_selected, route_selected,date]' , function(newValues, oldValues){
        if ($scope.route_selected && $scope.date){
            var route_id = JSON.parse($scope.route_selected).route_id;
            $scope.route_color = JSON.parse($scope.route_selected).route_color;

            Fctory.getTrips(route_id,$scope.direction_selected,dateToDateStr($scope.date), function(data){

                $scope.trips = data;
                if ($scope.trips[0]){
                    $scope.trips[0].selected = true;
                    $scope.getTrip($scope.trips[0]);
                }
                else{
                    FG_stops.clearLayers();
                    FG_iti.clearLayers();
                }
                $scope.$apply();
            });
        }
    });

});



app.config(function($mdDateLocaleProvider) {
    //https://material.angularjs.org/latest/api/service/$mdDateLocaleProvider
    // Example of a French localization.
    $mdDateLocaleProvider.months = ['janvier', 'février', 'mars', 'avril','mai','juin','juillet','aout','septembre','octobre','novembre','décembre'];
    $mdDateLocaleProvider.shortMonths = ['janv', 'févr', 'mars', 'avr','mai','juin','juil','aout','sep','oct','nov','dec'];
    $mdDateLocaleProvider.days = ['dimanche', 'lundi', 'mardi', 'mercredi','jeudi','vendredi','samedi','dimanche'];
    $mdDateLocaleProvider.shortDays = ['Di', 'Lu', 'Ma', 'Me','Je','Ve','Sa'];
    // Can change week display to start on Monday.
    $mdDateLocaleProvider.firstDayOfWeek = 1;
    $mdDateLocaleProvider.msgCalendar = 'Calendrier';
    $mdDateLocaleProvider.msgOpenCalendar = 'Ouvrir le calendrier';
});
