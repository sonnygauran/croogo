$(document).ready(function(){
    window['GEOMAP_SERVICES'] = {
        standard: [{
                id: "OSM", type: "tiled",
                attr: "<?= Configure::read('Tile.attr') ?>",
                src: function (view) {
                    return "<?= Configure::read('Tile.main.url') ?>/"
                        + view.zoom + "/"
                        + view.tile.column + "/"
                        + view.tile.row
                        + ".<?= Configure::read('Tile.main.ext') ?>";}}],
        transparent: [{
                id: "OSM", type: "tiled",
                attr: "<?= Configure::read('Tile.attr') ?>",
                src: function (view) {
                    return "https://tiles.mapbox.com/v3/meteomedia.weatherph-temperature/"
                        + view.zoom + "/"
                        + view.tile.column + "/"
                        + view.tile.row
                        + ".png";}}],
        half_transparent: [{
                id: "OSM", type: "tiled",
                attr: "<?= Configure::read('Tile.attr') ?>",
                src: function (view) {
                    return "<?= Configure::read('Tile.tiles') ?>/halftransparent/"
                        + view.zoom + "/"
                        + view.tile.column + "/"
                        + view.tile.row
                        + ".png";}}],
        outline: [{
                id: "OSM", type: "tiled",
                attr: "<?= Configure::read('Tile.attr') ?>",
                src: function (view) {
                    return "<?= Configure::read('Tile.tiles') ?>/outline/"
                        + view.zoom + "/"
                        + view.tile.column + "/"
                        + view.tile.row
                        + ".png";}}]
    }
    window['UNIT_TEMPERATURE'] = 'celsius';
        
    
    
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

        services: window['GEOMAP_SERVICES'].standard,
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
                
                var $station_readings = readings; // the complete retrieved stations
                //console.log($stationReadings);
                var cr_temperature, cr_wind, cr_precip, cr_humidity, cr_symbol;
                var sr_temperature, sr_wind, sr_precip, sr_humidity, sr_symbol;                
                
                $('.current.readings-location').html($station_readings.station_name);
                
                if($station_readings.reading.status == 'ok'){
                    
                    showReadings();
                    
                    $('.last-update').html($station_readings.reading.update);

                    cr_temperature = $station_readings.reading.temperature;
                    cr_wind = $station_readings.reading.wind_speed;
                    cr_precip = $station_readings.reading.precipitation;
                    cr_humidity = $station_readings.reading.relative_humidity;

                    $('.current.temperature span').html(cr_temperature);
                    $('.current.wind span').html(cr_wind);
                    $('.current.precipitation span').html(cr_precip);
                    $('.current.humidity span').html(cr_humidity);
                    
                    var weather_symbol = $station_readings.reading.weather_symbol;
                    
//                    console.error(weather_symbol);
                    
                    if(weather_symbol.hasOwnProperty('symbol')) $('#info .readings .symbol:eq(0)').addClass(weather_symbol.symbol);
                    $('.current.time').html($station_readings.reading.update);
                    
                }else{
                    hideReadings();
                }
                
                if($station_readings.forecast.status == 'ok'){
                    
                    showForecast();
                    
//                    console.error($station_readings.forecast);
                    
                    for (var key in $station_readings.forecast) {
                        
                        if(key != 'status'){
                        
                            sr_temperature = $station_readings.forecast[key].temperature;
                            sr_wind = $station_readings.forecast[key].wind_speed;
                            sr_precip = $station_readings.forecast[key].precipitation;
                            sr_humidity = $station_readings.forecast[key].relative_humidity;

//                            console.error(key);

                            var weather_symbol = $station_readings.forecast[key].weather_symbol;
                            
//                            console.error(weather_symbol);
                            
                            if(weather_symbol.hasOwnProperty('symbol')) $('.' + key + '-hour .symbol').addClass(weather_symbol.symbol);
                            
                            $('.' + key + '-hour.time').html($station_readings.forecast[key].localtime_range);
                            $('.' + key + '-hour .temperature span').html(sr_temperature);
                            $('.' + key + '-hour .wind span').html(sr_wind);
                            $('.' + key + '-hour .precipitation span').html(sr_precip);
                            $('.' + key + '-hour .humidity span').html(sr_humidity);

                        }

                    } 
                    
                    

                }else{
                    hideForecast();
                }
                
                $('.loader').fadeOut();
                $('.detail-page-link a').attr({href: '<?= $this->webroot ?>view/'+id});
                
            }
        });
    }



    $stationsPagasa = new Array();
    window['STATIONS'] = {
        pagasa: null,
        meteomedia: null
    };
    
    remapStations();

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
        {id: 'IVa', box: [119.14123511718803, 14.038008352438528, 123.151244882812, 14.211640744046566]},
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
                        
                        console.error($current.box);
                        setTimeout(getDataLayer, 1000);
                    }
                }
            } // END IF
        });
	});
    $('#map ul').css({
        'position': 'absolute',
        'background-color': '#333333',
        'bottom': 0,
        'left': 0,
        'list-style-type': 'none',
        'max-width': '100%',
        'padding': '4px 0 4px 0',
        'margin': 0,
        'width': '100%',
//        'opacity': 1,
        'font-size': '6.5pt',
        'text-transform': 'uppercase'
    });
    $('#map ul li').css({
        'margin-left': '6px',
        'color': 'white'
    });
    getForecast(984290);
});

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
    
function remapStations() {
    if (window['STATIONS'].pagasa == null) {
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
                window['STATIONS'].pagasa = $stationsPagasa;

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

                        window['STATIONS'].meteomedia = $stations;
                        mapStations($stations); // now the stations are complete
                        $('select[name=philippine-regions]').find('option[data-region-id=Philippines]').attr('selected','selected');
                        $('select[name=philippine-regions]').change();
                    }
                });

            }


        });
    } else {
        mapStationsPagasa(window['STATIONS'].pagasa);
        mapStations(window['STATIONS'].meteomedia);
    }
}

function removeStations(){
    $('#map').geomap('empty');
}

    function getDataLayer() {
        var bbox = '';
        //var url = 'http://alpha.meteomedia-portal.com/services/wetter4.php?api_key=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&&srs=EPSG:900913&';
        //var url = 'http://alpha.meteomedia-portal.com/services/wetter4.php?api_key=portal-efd339395c80ad957acb695bb9758399&p=QFF&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&';
        
        
        var $actual = $('#map').geomap('option','bbox');
        //$actual = eval($actual);
        
        // x1=111.32714843750325&x2=135.67285156249676&y1=0.8402895756535625&y2=24.41201768480203
        //        bbox +=  'x1='+$actual[0];
        //        bbox += '&x2='+$actual[2];
        //        bbox += '&y2='+$actual[3];
        //        bbox += '&y1='+$actual[1];
        
        bbox +=  'lon[0]='+$actual[0];
        bbox += '&lon[1]='+$actual[2];
        bbox += '&lat[0]='+$actual[3];
        bbox += '&lat[1]='+$actual[1];
        
        console.error(bbox);
        
        // Available layers
        var dataLayers = window["DATA_LAYERS"];
        // The currently-selected layer
        var dataLayer = window["DATA_LAYER"]; 
        
        console.error('x~>'+dataLayer);
        if (dataLayer == 'temperature' || dataLayer == 'pressure') {
            var url = '<?= Router::url('/', true) ?>weatherph/resources/data_layer/'+dataLayer+'/'+eval('dataLayers.'+dataLayer)+'&';
            url += bbox;
            
            console.error(url);
            $('.data-layer').animate({opacity: 0}, 600, function(){
                $('.data-layer').css('background-image', 'url('+url+')');
                $('.data-layer').animate({opacity: 1}, 600, function(){
                    //
                });
            });
            
            
            
        }
        
        
    }
function redrawMap(){
    var dataLayer = window['DATA_LAYER'];
    var serviceName = 'standard';
    
    if (dataLayer != null) {
        switch (dataLayer) {
            case 'temperature':
                serviceName = 'transparent';
                $('.scale-celsius').show();
                $('.scale-fahrenheit').hide();
                $('.scale-temperature').show();
                $('.unit-buttons').show();
                $('.scale-pressure').hide();
                removeStations();
                break;
            case 'pressure':
                removeStations();
                $('.scale-temperature').hide();
                $('.unit-buttons').hide();
                $('.scale-pressure').show();
                serviceName = 'outline';
                break;
            case 'stations':
                $('.scale-temperature').hide();
                $('.scale-pressure').hide();
                $('.unit-buttons').hide();
                remapStations();
                break;
            default:
                $('.scale-temperature').hide();
                break;
        }
    }
    var service = eval('window["GEOMAP_SERVICES"].'+serviceName);
    getDataLayer();
    
    $("#map").geomap({services: service});
}

function onionSkinMap() {
    var mapOpacity = 1;
    var dataLayer = window['DATA_LAYER'];
    
    if (dataLayer != null) {
        switch (dataLayer) {
            case 'temperature':
                break;
            case 'pressure':
                break;
        }
    }
    
    $('#map').animate({
        opacity: mapOpacity
    }, 350);
}

$(function(){
    $('.loader').css('opacity', 0.8);
    $('.video-viewport').hide();
    // Layer selector toggle

    $('.data-layers a').on('click', function(){
        event.preventDefault();
        
        var $video = $('.video-viewport');
        var $map = $('.map-viewport');
        
        var _name = $(this).attr('data-name');
        
        window["DATA_LAYER"] = _name;
        console.error('Set~>'+window["DATA_LAYER"]);
        
           

        //Temperature unit toggle
        
        $('#fahrenheit-switch').on('click', function(){
            window['UNIT_TEMPERATURE'] = 'fahrenheit';
            $('.scale-celsius').hide(0, function(){
                $('.scale-fahrenheit').show();
            });
        });
        
        $('#celsius-switch').on('click', function(){
            window['UNIT_TEMPERATURE'] = 'celsius';
            $('.scale-fahrenheit').hide(0, function(){
                $('.scale-celsius').show();
            });
        });
        
        // Layer selector toggle


        if ($(this).attr('data-type') == 'movie') {
            $('.scale-temperature').hide();
            $('.scale-pressure').hide();
            var $movie = $('#movie-'+_name); // The markup
            var _content = eval("window['MOVIE_CONTENT']."+_name);
            
            $map.fadeOut(1000, function(){
                console.error(eval("window['MOVIE_CONTENT']."+_name));
                $video
                    .html(eval("window['MOVIE_CONTENT']."+_name));
                    console.error($video);
                $video
                    .fadeIn(1000)
                    .find('video').show();
            });
            
        } else {
            console.error('is not movie');
            var videoVisible = $video.is(':visible');
            var mapVisible = $map.is(':visible');
            if (videoVisible) {
                console.error('video visible');
                $video.fadeOut(1000, function(){
                    $map.fadeIn(1000);
                    redrawMap();
                });
            } else {
                var opaque = { opacity: 1 };
                var transparent = { opacity: 0.5 };
                $('#map').animate(transparent, 600, function(){
                    redrawMap();
                    $('#map').animate(opaque, 600, function(){
                        onionSkinMap();
                    });
                });
                    
                if (mapVisible) {
                    console.error('is visible');
                } else {
                    console.error('is invisible');
                }
            }
            
        }
        
        
    });
});
