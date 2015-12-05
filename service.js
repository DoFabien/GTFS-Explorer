app.factory('Fctory',function(){
    var factory = {


        getRoutes:function(exception_route_type,callback){
            $.ajax({
                type: "GET",
                data:{exception_route_type:exception_route_type},
                url: 'php/get_routes.php',
                dataType:'json',
                success: function(data){
                    return callback(data);

                }
            });
        },

        getTrips:function(route_id,direction,date,callback){
            $.ajax({
                type: "GET",
                url: 'php/get_trips.php',
                data:{route_id:route_id, direction:direction, date:date},
                dataType:'json',
                success: function(data){
                    return callback(data);

                }
            });
        },
        getStopsByTrip:function(trip_id,callback){
            $.ajax({
                type: "GET",
                url: 'php/get_stops_by_trip.php',
                data:{trip_id:trip_id},
                dataType:'json',
                success: function(data){
                    return callback(data);

                }
            });
        },

        getItiByTrip:function(trip_id,callback){
            $.ajax({
                type: "GET",
                url: 'php/get_iti_by_trip.php',
                data:{trip_id:trip_id},
                dataType:'text',
                success: function(data){
                    return callback(data);
                }
            });
        },
        getDates:function(callback){
            $.ajax({
                type: "GET",
                url: 'php/get_dates.php',
                dataType:'json',
                success: function(data){
                    return callback(data);

                }
            });
        },


    }
    return factory;
});
