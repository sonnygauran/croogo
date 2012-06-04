$(document).ready(function(){
var map = $("#map").geomap({
    center: [123.5, 12.902712695115516], //to fit weather animations
        // was[ 121.750488, 12.698865 ],
    zoom: 5,
    scroll: 'off',
    cursors: {
        static: "default",
        pan: "default",
        zoom: "default",
        drawPoint: "default",
        drawLineString: "default",
        drawPolygon: "default",
        measureLength: "default",
        measureArea: "default"
    },
    //http://a.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/56590/256/5/15/12.png

    //Tiledrawer Maps

    services: [
    {
        id: "OSM",
        type: "tiled",
        src: function (view) {
        return "http://c.tiles.mapbox.com/v3/mapbox.mapbox-streets/"
        + view.zoom + "/"
        + view.tile.column + "/"
        + view.tile.row
        + ".png";
        },
        attr: "¬© OpenStreetMap & contributors, CC-BY-SA"
        
//          return "http://192.168.1.34:8888/convert.php?zoom="
//          + view.zoom + "&column="
//          + view.tile.column + "&row="
//          + view.tile.row
//          + "&mode=1";
//          },
//          attr: "¬© OpenStreetMap & contributors, CC-BY-SA"
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
//    console.log(result);
//    console.log(e);
    
    console.log(JSON.stringify(result));
    $.each(result, function () {
        outputHtml += ("<p>Found a " + this.type + " at " + this.coordinates + "</p>");
        console.error(this.id);
        
        $stations = new Array();
        $('.loader').fadeIn();
        getForecast(this.id);
        return;
    });
    }
});

function getForecast(id) {
    console.error('<?php Router::url($this->webroot) ?>');
    console.error('<?= $this->webroot ?>weatherph/weatherph/getForecast/'+id+'/3/3h');
    
$.ajax({
            type:     'GET',
            url:      '<?= $this->webroot ?>weatherph/weatherph/getForecast/'+id+'/3/3h',
            cache:    true,
            success:  function(readings) {
                
                var $stationReadings = readings; // the complete retrieved stations
                //console.log($stationReadings);
                var cr_temperature, cr_wind, cr_precip, cr_humidity, cr_symbol;
                var sr_temperature, sr_wind, sr_precip, sr_humidity, sr_symbol;                
                
                $('.current.readings-location').html($stationReadings.ort1);
                
                if($stationReadings.reading.status == 'ok'){
                    
                    showReadings();
                    
                    $('.last-update').html($stationReadings.reading.update);

                    cr_temperature = $stationReadings.reading.tl;
                    cr_wind = $stationReadings.reading.ff;
                    cr_precip = $stationReadings.reading.rr;
                    cr_humidity = $stationReadings.reading.rh;

                    $('.current.temperature span').html(cr_temperature);
                    $('.current.wind span').html(cr_wind);
                    $('.current.precipitation span').html(cr_precip);
                    $('.current.humidity span').html(cr_humidity);
                    $('#info .readings .symbol:eq(0)').addClass($stationReadings.reading.sy);
                    $('.current.time').html($stationReadings.reading.uthc);
                    
                }else{
                    hideReadings();
                }
                
                if($stationReadings.forecast.status == 'ok'){
                    
                    showForecast();
                
                    for (var key in $stationReadings.forecast) {
                        sr_temperature = $stationReadings.forecast[key].tl;
                        sr_wind = $stationReadings.forecast[key].ff;
                        sr_precip = $stationReadings.forecast[key].rr;
                        sr_humidity = $stationReadings.forecast[key].rh;

                        $('.' + key + '-hour .symbol').addClass($stationReadings.forecast[key].sy);
                        $('.' + key + '-hour.time').html($stationReadings.forecast[key].utch);
                        $('.' + key + '-hour .temperature span').html(sr_temperature);
                        $('.' + key + '-hour .wind span').html(sr_wind);
                        $('.' + key + '-hour .precipitation span').html(sr_precip);
                        $('.' + key + '-hour .humidity span').html(sr_humidity);

                    } 
                    
                    

                }else{
                    hideForecast();
                }
                
                $('.loader').fadeOut();
                $('.detail-page-link a').attr({href: '<?= $this->webroot ?>view/'+id});
                
            }
        });
     
//        $('.loader').fadeOut();
//        $('.detail-page-link a').attr({href: '<?= $this->webroot ?>view/'+id});
//        console.error('DOne fading out');
}

// Show/hide forecasts depending on availability

function hideForecast(){
    $('.day-forecast').fadeOut(function(){
        $('.no-forecast').fadeIn();
    });
}

function showForecast(){
    $('.day-forecast').fadeIn(function(){
        $('.no-forecast').fadeOut();
    });
}

function hideReadings(){
    $('.readings.shadow').fadeOut(function(){
        $('.no-readings').fadeIn();
    });
}

function showReadings(){
    $('.readings.shadow').fadeIn(function(){
        $('.no-readings').fadeOut();
    });
}

//Stations
//var $data = {
//stations : [
//	{
//		id: 26481,
//		name: 'Iloilo',
//		coordinates: [122.5667, 10.7]
//	},
//	{
//		id: 26437,
//		name: 'Alabat',
//		coordinates: [122.0167, 14.0833]
//	},
//		{
//		id: 26395,
//		name: 'Aparri',
//		coordinates: [121.6333, 18.3667]
//	},
//		{
//		id: 26409,
//		name: 'Baguio',
//		coordinates: [120.6, 16.4167]
//	},
//		{
//		id: 26412,
//		name: 'Cabanatuan',
//		coordinates: [120.9667, 15.4833]
//	},
//		{
//		id: 26390,
//		name: 'Vigan',
//		coordinates: [120.3833, 17.5667]
//	},
//		{
//		id: 26527,
//		name: 'Surallah/Allah Valley',
//		coordinates: [124.75, 6.3667]
//	},
//		{
//		id: 26426,
//		name: 'Sangley Point',
//		coordinates: [120.9167, 14.5]
//	},
//		{
//		id: 26456,
//		name: 'Romblon',
//		coordinates: [122.2667, 12.5833]
//	},
//		{
//		id: 26499,
//		name: 'Pagadian',
//		coordinates: [123.4667, 7.8333]
//	},
//]
//}

$stationsPagasa = new Array();
$.ajax({
    type:     'GET',
    url :     '<?= $this->webroot ?>weatherph/weatherph/getStations/pagasa',
    cache:    false,
    success: function(data) {
        var $retrievedStations = data; // the complete retrieved stations
        for (var key in $retrievedStations) {
            var $currentRetrievedStation = $retrievedStations[key]; // current station on the loop
            //console.log($currentRetrievedStation);
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
            url :     '<?= $this->webroot ?>weatherph/weatherph/getStations/meteomedia',
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
    //console.log($stationsArray);
    for (var key in $stationsArray) {
        $currentStation = $stationsArray[key];
        $('#map').geomap("append", {
        	id: $currentStation.id,
        	name: $currentStation.name,
        	type:'Point',                
        	coordinates: $currentStation.coordinates
        }, {strokeWidth: "1px", height: "6px", width: "6px", radius: "8px", color: "#dd2222", fillOpacity: "0", strokeOpacity: "1"},true);
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
        }, {strokeWidth: "1px", height: "7px", width: "7px", radius: "8px", color: "#2E4771", fillOpacity: "0", strokeOpacity: "1"},true);
    }

}

//Region selector
//var $centerMap = [
//	{id: 'NCR', center: [ 121.030884, 14.539721 ], zoom: 11},
//	{id: 'VI', center: [ 122.563477, 10.719984 ], zoom: 8},
//	{id: 'ARMM', center: [ 121.981201, 6.489983 ], zoom: 7},
//];

var $boxMap = [
    
        //MAJOR AREAS
        //These values were just taken directly from wetter4. No conversion.
        {id: 'Philippines', box: [109.43750000000374,5.008732666086105,137.56249999999628,20.55552655903733]},
        {id: 'Luzon', box: [115.21875000000186,12.992620600954227,129.28124999999815,20.641882002574366]},
	{id: 'VisMin', box: [118.18475000000187,5.729469014423421,132.24724999999813,13.607339308212687]},
	{id: 'Palawan', box: [113.58875000000187,4.629597878684261,127.65124999999814,12.531566520871163]},
        
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
                	
                	for (var key in $boxMap) { // let's traverse the $boxMap
						if ($boxMap[key].id == $region) {  // Initially matches 'data-region-id' with 'NCR'
							$current = $boxMap[key]; // the current $boxMap record

							console.log($current.box);
							$('#map').geomap({ // Then set the value from the $boxMap
								bbox: $current.box
							});
						}
					}
                } // END IF
              });
	});

//			$('#upak').click(function(){
//				$(".geo-map").geomap("opacity", 70/100);
//				$("#map").css({opacity: 1});
//				//.append();
//				
//          });
//			$('#reupak').click(function(){
//				$(".geo-map").geomap("opacity", 100/100);
//				$("#map").css({opacity: 1});
//				//.append();
//				
//          });

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
getForecast(984290);
});
$(function(){
        $('.loader').css('opacity', 0.8);
        var windContent = '<?php echo str_replace("\n", "\\", (<<<ECHO
<video id="video-wind" width="554" height="554" controls="controls" style="display: none;">
    <source src="{$this->webroot}assets/theme/weatherph/vid/wind.mp4" type="video/mp4" />
    <source src="{$this->webroot}assets/theme/weatherph/vid/wind.webm" type="video/webm" />
    Your browser does not support the video tag.
</video>
ECHO
));
    ?>';
        var precipContent = '<?php echo str_replace("\n", "\\", (<<<ECHO
<video id="video-precipitation" width="554" height="554" controls="controls" style="display: none;">
    <source src="{$this->webroot}assets/theme/weatherph/vid/precip.mp4" type="video/mp4" />
    <source src="{$this->webroot}assets/theme/weatherph/vid/precip.webm" type="video/webm" />
    Your browser does not support the video tag.
</video>
ECHO
)); ?>';
        
        var content = {
            wind:          windContent,
            precipitation: precipContent
        };
        
        // Layer selector toggle

        $('#link-map').on('click', function(){
            //event.preventDefault();
            $('.video-viewport').hide(1, function(){
                $('.map-viewport').show();
            });
            
            
        });

        $('.movies.dropdown a').on('click', function(){
            var _name = $(this).attr('data-name');
            var _content = eval('content.'+_name);
            
            $('.video-viewport').show().html(_content);
            event.preventDefault();
            $('.map-viewport').hide();
            $('#video-'+_name).show();
        });
    });