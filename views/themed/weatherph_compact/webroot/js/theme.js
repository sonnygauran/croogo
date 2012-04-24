$(document).ready(function(){
var map = $("#map").geomap({
    center: [ 121.019825, 14.557263 ],
    zoom: 6,
    scroll: 'off',
    
    //http://a.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/56590/256/5/15/12.png

    //Tiledrawer Maps

    services: [
    {
        id: "OSM",
        type: "tiled",
        src: function (view) {
        return "http://tile.stamen.com/toner/"
        + view.zoom + "/"
        + view.tile.column + "/"
        + view.tile.row
        + ".png";
        },
        attr: "© OpenStreetMap & contributors, CC-BY-SA"
        
//          return "http://192.168.1.34:8888/convert.php?zoom="
//          + view.zoom + "&column="
//          + view.tile.column + "&row="
//          + view.tile.row
//          + "&mode=1";
//          },
//          attr: "© OpenStreetMap & contributors, CC-BY-SA"
    }
    ],
    tilingScheme: {
    tileWidth: 256,
    tileHeight: 256,
    levels: 18,
    basePixelSize: 156543.03392799936,
    origin: [-20037508.342787, 20037508.342787]
    }
    
});
//map.geomap( "option", "cursors", {
//  static: "crosshair",
//  pan: "crosshair",
//  zoom: "crosshair",
//  drawPoint: "crosshair",
//  drawLineString: "crosshair",
//  drawPolygon: "crosshair",
//  measureLength: "crosshair",
//  measureArea: "crosshair"
//} );
map.geomap({
    //Find mode
    mode: "find",
    click: function(e, geo) {
        var outputHtml = "";
    result = $('#map').geomap("find", geo, 6);
    console.log(result);
    console.log(e);
    
    console.log(JSON.stringify(result));
    $.each(result, function () {
        outputHtml += ("<p>Found a " + this.type + " at " + this.coordinates + "</p>");
        console.log(this.id);
        
        $stations = new Array();
        getForecast(this.id);
        return;
    });
    }
});

function getForecast(id) {
$.ajax({
            type:     'GET',
            url:        '/weatherph/weatherph/getForecast/'+id+'/2/3h',
            cache:    false,
            success:  function(readings) {
                
                var $stationReadings = readings; // the complete retrieved stations
                var d = new Date();
                var hr = d.getHours();
                var utc, utch;
                var cr_temperature, cr_wind, cr_precip, cr_humidity;
                var sr_temperature, sr_wind, sr_precip, sr_humidity;                
                
                $('.current.readings-location').html($stationReadings.ort1);
                
                if(hr >=0 && hr <3){
                    cr_temperature = $stationReadings.utc00.tl;
                    cr_wind = $stationReadings.utc00.ff;
                    cr_precip = $stationReadings.utc00.rr;
                    cr_humidity = $stationReadings.utc00.rh;
                    utch = 0;
                }else if(hr >=3 && hr <6){
                    cr_temperature = $stationReadings.utc03.tl;
                    cr_wind = $stationReadings.utc03.ff;
                    cr_precip = $stationReadings.utc03.rr;
                    cr_humidity = $stationReadings.utc03.rh;
                    utch = 3;
                }else if(hr >=6 && hr <9){
                    cr_temperature = $stationReadings.utc06.tl;
                cr_wind = $stationReadings.utc06.ff;
                cr_precip = $stationReadings.utc06.rr;
                cr_humidity = $stationReadings.utc06.rh;
                    utch = 6;
                }else if(hr >=9 && hr <12){
                    cr_temperature = $stationReadings.utc09.tl;
                cr_wind = $stationReadings.utc09.ff;
                cr_precip = $stationReadings.utc09.rr;
                cr_humidity = $stationReadings.utc09.rh;
                    utch = 9;
                }else if(hr >=12 && hr <15){
                    cr_temperature = $stationReadings.utc12.tl;
                cr_wind = $stationReadings.utc12.ff;
                cr_precip = $stationReadings.utc12.rr;
                cr_humidity = $stationReadings.utc12.rh;
                    utch = 12;
                }else if(hr >=15 && hr <18){
                    cr_temperature = $stationReadings.utc15.tl;
                cr_wind = $stationReadings.utc15.ff;
                cr_precip = $stationReadings.utc15.rr;
                cr_humidity = $stationReadings.utc15.rh;
                    utch = 15;
                }else if(hr >=18 && hr <21){
                    cr_temperature = $stationReadings.utc18.tl;
                cr_wind = $stationReadings.utc18.ff;
                cr_precip = $stationReadings.utc18.rr;
                cr_humidity = $stationReadings.utc18.rh;
                    utch = 18;
                }else{
                    cr_temperature = $stationReadings.utc21.tl;
                cr_wind = $stationReadings.utc21.ff;
                cr_precip = $stationReadings.utc21.rr;
                cr_humidity = $stationReadings.utc21.rh;
                    utch = 21;
                }
                
                
                for (var key in $stationReadings) {
                    //var $currentStationReadings = $stationReadings[key];
                    
                    if(key == "utc00"){
                        cr_temperature = $stationReadings.utc00.tl;
                        cr_wind = $stationReadings.utc00.ff;
                        cr_precip = $stationReadings.utc00.rr;
                        cr_humidity = $stationReadings.utc00.rh;
                        utch = 0;
                    }else if(key == "utc03"){
                        cr_temperature = $stationReadings.utc03.tl;
                        cr_wind = $stationReadings.utc03.ff;
                        cr_precip = $stationReadings.utc03.rr;
                        cr_humidity = $stationReadings.utc03.rh;
                        utch = 3;
                    }else if(key == "utc06"){
                        cr_temperature = $stationReadings.utc06.tl;
                    cr_wind = $stationReadings.utc06.ff;
                    cr_precip = $stationReadings.utc06.rr;
                    cr_humidity = $stationReadings.utc06.rh;
                        utch = 6;
                    }else if(key == "utc09"){
                        cr_temperature = $stationReadings.utc09.tl;
                    cr_wind = $stationReadings.utc09.ff;
                    cr_precip = $stationReadings.utc09.rr;
                    cr_humidity = $stationReadings.utc09.rh;
                        utch = 9;
                    }else if(key == "utc12"){
                        cr_temperature = $stationReadings.utc12.tl;
                    cr_wind = $stationReadings.utc12.ff;
                    cr_precip = $stationReadings.utc12.rr;
                    cr_humidity = $stationReadings.utc12.rh;
                        utch = 12;
                    }else if(key == "utc15"){
                        cr_temperature = $stationReadings.utc15.tl;
                    cr_wind = $stationReadings.utc15.ff;
                    cr_precip = $stationReadings.utc15.rr;
                    cr_humidity = $stationReadings.utc15.rh;
                        utch = 15;
                    }else if(key == "utc18"){
                        cr_temperature = $stationReadings.utc18.tl;
                    cr_wind = $stationReadings.utc18.ff;
                    cr_precip = $stationReadings.utc18.rr;
                    cr_humidity = $stationReadings.utc18.rh;
                        utch = 18;
                    }else{
                        cr_temperature = $stationReadings.utc21.tl;
                    cr_wind = $stationReadings.utc21.ff;
                    cr_precip = $stationReadings.utc21.rr;
                    cr_humidity = $stationReadings.utc21.rh;
                        utch = 21;
                    }
                    
                    //alert(key);
                }
                
                //utch = utch + "";
                
                //utch = (utch.length < 2)? "0" + utch : utch;
                
                //utch = "utc" + utch;
                
                $('.current.temperature span').html(cr_temperature);
                $('.current.wind span').html(cr_wind);
                $('.current.precipitation span').html(cr_precip);
                $('.current.humidity span').html(cr_humidity);
                
                /*
                var utch = 0;
                var utcsr, utchsr;
                var increment3 = 3; 
                var cntr = 0;
                
                for(utch = utc; utch<=21; utch=utch + increment3 ){
                
//                    cntr = cntr + increment3;
//                    
//                    utchsr = utch + "";
//                
//                    utchsr = (utchsr.length < 2)? "0" + utchsr : utchsr;
//                    utchsr = "utc" + utchsr;
//                    
//                    utcsr = utchsr;
                    
                    alert(utch);
//                    
//                    sr_temperature = $stationReadings[utcsr].tl;
//                    sr_wind = $stationReadings[utcsr].ff;
//                    sr_precip = $stationReadings[utcsr].rr;
//                    sr_humidity = $stationReadings[utcsr].rh;
//                    
//                    $('.'+ cntr + '-hour .temperature span').html(sr_temperature);
//                    $('.'+ cntr + '-hour .wind span').html(sr_wind);
//                    $('.'+ cntr + '-hour .precipitation span').html(sr_precip);
//                    $('.'+ cntr + '-hour .humidity span').html(sr_humidity);
                
                    
                }
                */
                
                
                
                
                
            }
        });
}
//Stations

var $data = {
stations : [
	{
		id: 26481,
		name: 'Iloilo',
		coordinates: [122.5667, 10.7]
	},
	{
		id: 26437,
		name: 'Alabat',
		coordinates: [122.0167, 14.0833]
	},
		{
		id: 26395,
		name: 'Aparri',
		coordinates: [121.6333, 18.3667]
	},
		{
		id: 26409,
		name: 'Baguio',
		coordinates: [120.6, 16.4167]
	},
		{
		id: 26412,
		name: 'Cabanatuan',
		coordinates: [120.9667, 15.4833]
	},
		{
		id: 26390,
		name: 'Vigan',
		coordinates: [120.3833, 17.5667]
	},
		{
		id: 26527,
		name: 'Surallah/Allah Valley',
		coordinates: [124.75, 6.3667]
	},
		{
		id: 26426,
		name: 'Sangley Point',
		coordinates: [120.9167, 14.5]
	},
		{
		id: 26456,
		name: 'Romblon',
		coordinates: [122.2667, 12.5833]
	},
		{
		id: 26499,
		name: 'Pagadian',
		coordinates: [123.4667, 7.8333]
	},
]
}

$stationsPagasa = new Array();
$.ajax({
    type:     'GET',
    url :     '/weatherph/weatherph/getStations/pagasa',
    cache:    false,
    success: function(data) {
        var $retrievedStations = data; // the complete retrieved stations
        for (var key in $retrievedStations) {
            var $currentRetrievedStation = $retrievedStations[key]; // current station on the loop
            $stationsPagasa.push({ // create a json object, and then save it to stations array
            	id: $currentRetrievedStation.id,
            	name: $currentRetrievedStation.name,
            	type:'Point',
            	coordinates: [
            	   $currentRetrievedStation.coordinates.longitude,
            	   $currentRetrievedStation.coordinates.latitude
        	   ]
            });
        }
        
        mapStationsPagasa($stationsPagasa); // now the stations are complete
        
        $stations = new Array();
        $.ajax({
            type:     'GET',
            url :     '/weatherph/weatherph/getStations/meteomedia',
            cache:    false,
            success: function(data) {
                var $retrievedStations = data; // the complete retrieved stations
                for (var key in $retrievedStations) {
                    var $currentRetrievedStation = $retrievedStations[key]; // current station on the loop
                    $stations.push({ // create a json object, and then save it to stations array
                        id: $currentRetrievedStation.id,
                        name: $currentRetrievedStation.name,
                        type:'Point',
                        coordinates: [
                        $currentRetrievedStation.coordinates.longitude,
                        $currentRetrievedStation.coordinates.latitude
                        ]
                    });
                }

                mapStations($stations); // now the stations are complete
            }
        });

    }
    
    
});


//Station output



// Uncomment this to see stations hard-coded on javascript
//for (var key in $data.stations) {
//    map.geomap("append", {
//    	id: $data.stations[key].id,
//    	name: $data.stations[key].name,
//    	type:'Point', coordinates: $data.stations[key].coordinates
//    }, true);
//}
//$('#map').geomap({
//    click: function(e, geo) {
//    	var outputHtml = "";
//        result = map.geomap("find", geo, 8);
//        $.each(result, function () {
//        	outputHtml += ("<p>Found a " + this.type + " at " + this.coordinates + "</p>");
//        	$('.details dt').html(this.name); 
//        	$('.details dd').html("Some readings here"); 
//        });
//        
//        $('.ad').append(outputHtml);
//    }
//
//});

//mapStations($data.stations);

function mapStationsPagasa($stationsArray) {
    // This loop maps the stations from the $stations fetched from getStations
    for (var key in $stationsArray) {
        $currentStation = $stationsArray[key];
        $('#map').geomap("append", {
        	id: $currentStation.id,
        	name: $currentStation.name,
        	type:'Point',                
        	coordinates: $currentStation.coordinates
        }, {strokeWidth: "1px", height: "6px", width: "6px", radius: "8px", color: "#dd2222", fillOpacity: "1", strokeOpacity: ".3"},true);
    }
}

function mapStations($stationsArray) {
    // This loop maps the stations from the $stations fetched from getStations
    for (var key in $stationsArray) {
        $currentStation = $stationsArray[key];
        $('#map').geomap("append", {
        	id: $currentStation.id,
        	name: $currentStation.name,
        	type:'Point',                
        	coordinates: $currentStation.coordinates
        }, {strokeWidth: "2px", height: "8px", width: "8px", radius: "8px", color: "#2E4771", fillOpacity: "1", strokeOpacity: ".3"},true);
    }

}

//Region selector
var $centerMap = [
	{id: 'NCR', center: [ 121.030884, 14.539721 ], zoom: 11},
	{id: 'VI', center: [ 122.563477, 10.719984 ], zoom: 8},
	{id: 'ARMM', center: [ 121.981201, 6.489983 ], zoom: 7},
];

var $boxMap = [
	//LUZON
	{id: 'NCR', box: [120.78025838964851, 14.340234924288968, 121.28150961035149, 14.739027102167846]},
	{id: 'CAR', box: [119.07531711718802, 15.860957319356404, 123.08532688281198, 19.004996360800135]},
	{id: 'I', box: [118.44909711718802, 15.347761824788998, 122.45910688281198, 18.500447360569783]},
	{id: 'II', box: [119.61914111718802, 15.538376429558836, 123.62915088281197, 18.687879180851954]},
	{id: 'III', box: [119.14123511718803, 14.038008352438528, 123.151244882812, 17.211640744046566]},
	{id: 'IVa', box: [119.14123511718803, 14.038008352438528, 123.151244882812, 17.211640744046566]},
	{id: 'IVb', box: [115.45532223437608, 7.961317655755968, 123.47534176562394, 14.424040675692801]},
	{id: 'V', box: [121.40991211718803, 11.813588529774567, 125.41992188281199, 15.019075443311895]},
	
	//VISAYAS
	{id: 'VI', box: [120.55847211718805, 9.096672666835465, 124.56848188281197, 12.33463548967992]},
	{id: 'VII', box: [121.62963911718803, 8.472372161745135, 125.63964888281197, 11.716788270049275]},
	{id: 'VIII', box: [122.86010711718802, 9.871452017038855, 126.87011688281196, 13.100879989039102]},

	//MINDANAO
	{id: 'IX', box: [120.69580111718803, 6.197898567731331, 124.70581088281199, 9.462607734406564]},
	{id: 'X', box: [122.68432611718804, 6.680975517225828, 126.69433588281198, 9.941798440553796]},
	{id: 'XI', box: [123.72802711718802, 5.4082107972443785, 127.73803688281197, 8.678778561939074]},
	{id: 'XII', box: [123.08532711718804, 5.364459981953138, 127.09533688281198, 8.635334367537935]},
	{id: 'XIII', box: [123.73352011718801, 7.525873210799716, 127.74352988281196, 10.779348910314807]},
	{id: 'ARMM', box: [117.97119123437608, 3.206332652787861, 125.99121076562393, 9.752369809194555]},

];

	$('select[name=philippine-regions]').change(function(){
		
			$("select[name=philippine-regions] option:selected").each(function () {
                
                if ($(this).attr('selected')) { // Is the current <option> selected?
                	$region = $(this).attr('data-region-id'); // the region id
                	
                	for (var key in $boxMap) { // let's traverse the $centerMap
						if ($boxMap[key].id == $region) {  // Initially matches 'data-region-id' with 'NCR'
							$current = $boxMap[key]; // the current $centerMap record

							console.log($current.box);
							$('#map').geomap({ // Then set the value from the $centerMap
								bbox: $current.box
							});
						}
					}
                } // END IF
              });
	});

			$('#upak').click(function(){
				$(".geo-map").geomap("opacity", 70/100);
				$("#map").css({opacity: 1});
				//.append();
				
          });
			$('#reupak').click(function(){
				$(".geo-map").geomap("opacity", 100/100);
				$("#map").css({opacity: 1});
				//.append();
				
          });

/*
        var $widther = 220;    
        var resizeTimer;
        
        resizeTimer = setTimeout(    function resizeIt(){
            console.log($(window).width());
            if ($(window).width() <= 1024) {
                $('#map').css('width', '810px');
            } else {
                $('#map').css('width', '720px');
            }
            
            $('.geo-content-frame').css('width', $('#map').css('width'));        
            clearTimeout(resizeTimer);
        }, 100);
    
        
        $(window).resize(function() {
            resizeTimer = setTimeout(    function resizeIt(){
                console.log($(window).width());
                if ($(window).width() <= '1024px') {
                    $('#map').css('width', '814px');
                } else {
                    $('#map').css('width', '720px');
                }
                
                $('.geo-content-frame').css('width', $('#map').css('width'));        
                clearTimeout(resizeTimer);
            }, 100);
    
        });
//widther has been commented out for the meanwhile as it breaks the layout upon initial load.
*/	  
getForecast(984250);
});
