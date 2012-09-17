var _name = "";
var $s = '';
var videoRegion = 'All';
var currentRegion = 'All';
var currentDataLayer = {
    'pressure'   : 0,
    'temperature': 0,
    'satellite'  : 0
};

var $boxMap = [
//MAJOR AREAS
{
    id: 'Philippines',
    box: [111.3134765625, 0.8349313860427184, 135.6591796875, 24.407137917727667]
},
{
    id: 'Luzon',
    box: [115.21875000000186,12.992620600954227,129.28124999999815,20.641882002574366]
},
{
    id: 'VisMin',
    box: [120.16796875,4.653695352486294,128.25439453125,12.551058133703483]
},
{
    id: 'Palawan',
    box: [116.54296874999999,6.402648405963896,122.62939453125001,12.404388944669792]
},

//LUZON
{
    id: 'NCR',
    box: [120.78025838964851, 14.340234924288968, 121.28150961035149, 14.739027102167846]
},
{
    id: 'CAR',
    box: [119.07531711718802, 15.860957319356404, 123.08532688281198, 19.004996360800135]
},
{
    id: 'I',
    box: [118.44909711718802, 15.347761824788998, 122.45910688281198, 18.500447360569783]
},
{
    id: 'II',
    box: [119.61914111718802, 15.538376429558836, 123.62915088281197, 18.687879180851954]
},
{
    id: 'III',
    box: [119.14123511718803, 14.038008352438528, 123.151244882812, 17.211640744046566]
},
{
    id: 'IVa',
    box: [119.14123511718803, 14.038008352438528, 123.151244882812, 14.211640744046566]
},
{
    id: 'IVb',
    box: [115.45532223437608, 7.961317655755968, 123.47534176562394, 14.424040675692801]
},
{
    id: 'V',
    box: [121.40991211718803, 11.813588529774567, 125.41992188281199, 15.019075443311895]
},

//VISAYAS
{
    id: 'VI',
    box: [120.55847211718805, 9.096672666835465, 124.56848188281197, 12.33463548967992]
},
{
    id: 'VII',
    box: [121.62963911718803, 8.472372161745135, 125.63964888281197, 11.716788270049275]
},
{
    id: 'VIII',
    box: [122.86010711718802, 9.871452017038855, 126.87011688281196, 13.100879989039102]
},

//MINDANAO
{
    id: 'IX',
    box: [120.69580111718803, 6.197898567731331, 124.70581088281199, 9.462607734406564]
},
{
    id: 'X',
    box: [122.68432611718804, 6.680975517225828, 126.69433588281198, 9.941798440553796]
},
{
    id: 'XI',
    box: [123.72802711718802, 5.4082107972443785, 127.73803688281197, 8.678778561939074]
},
{
    id: 'XII',
    box: [123.08532711718804, 5.364459981953138, 127.09533688281198, 8.635334367537935]
},
{
    id: 'XIII',
    box: [123.73352011718801, 7.525873210799716, 127.74352988281196, 10.779348910314807]
},
{
    id: 'ARMM',
    box: [117.97119123437608, 3.206332652787861, 125.99121076562393, 9.752369809194555]
}

];

$(document).ready(function(){
    window['LEAFLET_TILES_SRC'] = {
        stations:    "<?= Configure::read('Tile.main.url') ?>/{z}/{x}/{y}.png",
        temperature: "<?= Configure::read('Tile.temperature.url') ?>/{z}/{x}/{y}.png",
        pressure:    "<?= Configure::read('Tile.pressure.url') ?>/{z}/{x}/{y}.png"
    };
    window['LEAFLET_TILES'] = {
        stations:    {},
        temperature: {},
        pressure:    {}
    };


    window['ATTRIBUTION'] = 'Weather Philippines Foundation';
    window['UNIT_TEMPERATURE'] = 'celsius';
    window['IMAGE_DATA_LAYER'] = new L.LayerGroup();
    window['STATIONS_LAYER'] = new L.LayerGroup();

    var map = new L.Map('map', {
        maxZoom: 10,
        minZoom: 5,
        zoom: 7,
        layers: [window['STATIONS_LAYER']],
        zoomControl: true
    });


    var ph = $boxMap[0].box;
    var southWest = new L.LatLng(ph[1],ph[0]),
    northEast = new L.LatLng(ph[3],ph[2]),
    bounds    = new L.LatLngBounds(southWest, northEast);

    $('#map').data('map', map);


    window['LEAFLET_TILES'].stations    = new L.TileLayer(window['LEAFLET_TILES_SRC'].stations,    {
        maxZoom: 18,
        attribution: window['ATTRIBUTION']
    });
    window['LEAFLET_TILES'].temperature = new L.TileLayer(window['LEAFLET_TILES_SRC'].temperature, {
        maxZoom: 18,
        attribution: window['ATTRIBUTION']
    });
    window['LEAFLET_TILES'].pressure    = new L.TileLayer(window['LEAFLET_TILES_SRC'].pressure,    {
        maxZoom: 18,
        attribution: window['ATTRIBUTION']
    });

    map
    .setView(new L.LatLng(14.5167, 121), 7)
    .addLayer(window['LEAFLET_TILES'].stations)
    .setMaxBounds(bounds);
    map.panTo(bounds.getCenter()).setZoom(7);

    var baseMaps = {
        "Stations": window['LEAFLET_TILES'].stations,
        "Temperature": window['LEAFLET_TILES'].temperature,
        "Pressure": window['LEAFLET_TILES'].pressure
    };
    var overlayMaps = {
        "Stations": window['STATIONS_LAYER']
    };

    map.on('dragend', function(){
        $('.province-select select option').removeAttr('selected');
        $('.province-select select option:first').attr('selected','selected');
    });

    setTimeout(function(){
        map.setZoom(4);
    }, 1600);

    $stationsOthers = new Array();
    window['STATIONS'] = {
        others: null,
        meteomedia: null
    };

    remapStations();

    $(window).load(function() {
        setTimeout(function() {
            $('select[name=philippine-regions]').change(function(){
                $("select[name=philippine-regions] option:selected").each(function () {

                    // TODO: Use an attribute instead of .text(). This function will break as soon as the text changes.

                    if($('.active-layer').text() !== 'Weather movies \u25bf'){
                        if ($(this).attr('selected')) { // Is the current <option> selected?
                            $region = $(this).attr('data-region-id'); // the region id

                            for (var key in $boxMap) { // let's traverse the $boxMap
                                if ($boxMap[key].id == $region) {  // Initially matches 'data-region-id' with 'NCR'
                                    $current = $boxMap[key].box; // the current $boxMap record
                                    // console.error($boxMap[key]);
                                    var southWest = new L.LatLng($current[1],$current[0]),
                                    northEast = new L.LatLng($current[3],$current[2]),
                                    bounds    = new L.LatLngBounds(southWest, northEast);

                                    var map = $('#map').data('map');

                                    var zoom = 7;
                                    if ($boxMap[key].id == 'Philippines') {
                                        zoom = 4;
                                    }
                                    if ($(this).parent('optgroup').hasClass('minor-area')) {
                                        zoom = 8;
                                    }

                                    map.panTo(bounds.getCenter()).setZoom(zoom);
                                    //                                console.log($current);
                                    setTimeout(getDataLayer, 1000);
                                }
                            }
                        }
                    } else if($('.active-layer').text() === 'Weather movies \u25bf'){

                        // Video switch

                        window["DATA_LAYER"] = _name;
                        // console.error('Set~>'+window["DATA_LAYER"]);

                        var $movie = $('#movie-'+_name); // The markup
                        var content = eval("window['MOVIE_CONTENT']."+_name);

                        switch ($(this).val()){
                            case 'All Philippines':
                                videoRegion = 'All';
                                 $s = 'All Philippines';
                            break;
                            case 'Luzon':
                                videoRegion = 'LUZON';
                                $s = 'Luzon';
                                break;
                            case 'Visayas/Mindanao':
                                videoRegion = 'VISAYAS_MINDANAO';
                                $s = 'Visayas';
                                break;
                            case 'Palawan/Sulu Sea':
                                videoRegion = 'PALWAN';
                                $s = 'Sulu';
                                break;
                        }

                        var src;
                        $.each($('#movie-'+_name + ' > source'), function(index, value){
                            src = $(this).attr('src');
                            new_src = src.replace(currentRegion, videoRegion);
                            $(this).attr('src', new_src);
                            console.error(new_src);
                            console.error('id:',$(this).val());
                        });
                        $('#movie-'+_name).load();
                        currentRegion = videoRegion;
                    }
                });
            });
        }, 300);
    });
    getForecast(984290); //Manila
});

/*
 *Ajax
 */
function getForecast(id) {
    // console.error('<?php Router::url($this->webroot) ?>');
    // console.error('<?= $this->webroot ?>weatherph/weatherph/getForecast/'+id+'/3/3h');

    $.ajax({
        type   : 'GET',
        url    : '<?= $this->webroot ?>weatherph/weatherph/getForecast/'+id+'/3/3h',
        cache  : true,
        success: function(readings) {
            var title = readings.station_name;
            title = title.replace('<br />', ' ');
            document.title =  "Weather Philippines Foundation | " +title  ;
            //                console.log(readings);
            var $station_readings = readings; // the complete retrieved stations
            // console.log($station_readings);
            var cr_temperature, cr_wind, cr_precip, cr_humidity, cr_symbol;
            var sr_temperature, sr_wind, sr_precip, sr_humidity, sr_symbol;
            var current_readings, cr_precip_hr_range;

            $('#readings-location').html($station_readings.station_name);
            $('#info .readings .symbol').removeAttr('class').addClass('symbol');

            if($station_readings.reading.status == 'ok'){

                showReadings();

                current_readings = $station_readings.reading;
                $('#last-update').html(current_readings.update);

                cr_temperature = current_readings.temperature;
                cr_wind = current_readings.wind_speed;
                cr_precip = current_readings.precipitation;
                cr_precip_hr_range = current_readings.precipitation_hr_range;
                cr_humidity = current_readings.relative_humidity;

                $('.current.temperature span').html(cr_temperature);
                $('.current.wind span').html(cr_wind);
                $('.current.precipitation span').html(cr_precip);
                $('.precipitation_hr_range').html(cr_precip_hr_range);
                $('.current.humidity span').html(cr_humidity);

                if ($station_readings.reading.weather_symbol) {
                    var weather_symbol = $station_readings.reading.weather_symbol;

                    //                        console.error(weather_symbol);

                    if(weather_symbol.hasOwnProperty('symbol') && weather_symbol !== '-') {
                        $('#info .readings .symbol:eq(0)').addClass(weather_symbol.symbol);
                    }else{
                        $('#info .readings .symbol:eq(0)').attr('class', 'symbol');
                    }
                    $('.current.time').html($station_readings.reading.update);
                }

            }else{
                hideReadings();
            }

            if($station_readings.forecast.status == 'ok'){

                showForecast();

                //console.error($station_readings.forecast);

                for (var key in $station_readings.forecast) {

                    if(key !== 'status'){

                        sr_temperature = $station_readings.forecast[key].temperature;
                        sr_wind = $station_readings.forecast[key].wind_speed;
                        sr_precip = $station_readings.forecast[key].precipitation;
                        sr_humidity = $station_readings.forecast[key].relative_humidity;

                        //console.error(key);

                        weather_symbol = $station_readings.forecast[key].weather_symbol;

                        //                            console.error(weather_symbol);
                        if(weather_symbol.hasOwnProperty('symbol') && weather_symbol !== '-') {
                            $('.' + key + '-hour .symbol').addClass(weather_symbol.symbol);
                        }else{
                            $('.' + key + '-hour .symbol').attr('class', 'symbol');
                        }

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

            $('#loader').fadeOut();
            $('.detail-page-link a').attr({
                href: '<?= $this->webroot ?>view/'+id
            });
        }
    });
}

function hideSelect(){
    $('.stations-only').fadeOut(function(){
        $('.stations-only').fadeIn();
    });
}
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

function isiPhone(){
    return (
        //Detect iPhone
        (navigator.platform.indexOf("iPhone") !== -1) ||
        //Detect iPod
        (navigator.platform.indexOf("iPod") !== -1)
        );
}

// console.error('<?= Router::url(null, true) ?>theme/weatherph/img/leaflet/marker-icon-red-small-transparent.png');
var StationIconWeb = L.Icon.extend({
    options: {
        iconUrl: '<?= Router::url(null, true) ?>theme/weatherph/img/leaflet/marker-icon-red-small-transparent.png',
        shadowUrl: '<?= Router::url(null, true) ?>/theme/weatherph/img/leaflet/marker-shadow.png',
        iconSize: new L.Point(8, 13),
        shadowSize: new L.Point(13, 13),
        iconAnchor: new L.Point(8, 13),
        popupAnchor: new L.Point(-4, -15),
        zIndexOffset: 1000
    }

});

var StationIconMobile = L.Icon.extend({
    options: {
        iconUrl: '<?= Router::url(null, true) ?>theme/weatherph/img/leaflet/marker-icon-red-small-transparent.png',
        shadowUrl: '<?= Router::url(null, true) ?>/theme/weatherph/img/leaflet/marker-shadow.png',
        iconSize: new L.Point(12, 20),
        shadowSize: new L.Point(20, 20),
        iconAnchor: new L.Point(12, 20),
        popupAnchor: new L.Point(-5, -20),
        zIndexOffset: 1000
    }
});

var stationIcon    = new StationIconWeb();
var meteomediaIcon = new StationIconWeb();
meteomediaIcon.options.iconUrl = '<?= Router::url(null, true) ?>theme/weatherph/img/leaflet/marker-icon-blue-small.png';

if (isiPhone() || (navigator.userAgent.match(/iPad/i) !== null)) {
    stationIcon    = new StationIconMobile();
    meteomediaIcon = new StationIconMobile('<?= Router::url(null, true) ?>theme/weatherph/img/leaflet/marker-icon-blue-small.png');
}

function mapStationsPagasa($stationsArray) {
    mapStations($stationsArray, stationIcon);
}

function mapStations($stationsArray, icon, stationName) {
    var _icon = meteomediaIcon;
    if (icon !== null) {
        _icon = icon;
    }

    // This loop maps the stations from the $stations fetched from getStations
    var counter = 0;
    var isiPad = navigator.userAgent.match(/iPad/i) !== null;

    for (var key in $stationsArray) {

        $currentStation = $stationsArray[key];
        var markerLocation = new L.LatLng($currentStation.coordinates[1], $currentStation.coordinates[0]);
        var marker = new L.Marker(markerLocation, {
            icon: _icon
        });

        /**
         * This would make MM stations on top of others
         */
        if (stationName != null) {
            if (stationName == 'meteomedia') {
                marker.setZIndexOffset(1);
            } else {
                marker.setZIndexOffset(0);
            }
        }

        var content = "<b>"+$currentStation.name+"</b>";
        //        if (isiPad || isiPhone()) {
        content += "<br /><a href=\"#\" class=\"marker-popup\" data-id=\""+$currentStation.id+" \">View Details</a>";
        //        }
        marker.bindPopup(content);

        marker.station = {
            id: $currentStation.id,
            name: $currentStation.name
        };

        marker.on('click', function(e){
            var self = this;
            $('.marker-popup').on('click', function(evt){
                evt.preventDefault();

                getForecast(self.station.id);

                self.closePopup();
            });
        });
        window['STATIONS_LAYER'].addLayer(marker);
    }
    $('#map').data('map').addLayer(window['STATIONS_LAYER']);
}

function remapStations() {
    $('.data-layer-label').hide();
    if (window['STATIONS'].others === null) {
        $.ajax({
            type   : 'GET',
            url    : '<?= Router::url(null, true) ?>weatherph/weatherph/getStations/others',
            cache  : false,
            success: function(data) {
                var $retrievedStations = data; // the complete retrieved stations
                for (var key in $retrievedStations) {
                    var $currentRetrievedStation = $retrievedStations[key]; // current station on the loop
                    //console.log($currentRetrievedStation);
                    $stationsOthers.push({ // create a json object, and then save it to stations array
                        id: $currentRetrievedStation.id,
                        name: $currentRetrievedStation.name,
                        type:'Point',
                        coordinates: [
                        $currentRetrievedStation.coordinates.longitude,
                        $currentRetrievedStation.coordinates.latitude
                        ]
                    });
                }
                // Temporary Hidden
                window['STATIONS'].others = $stationsOthers;


                $stations = new Array();
                $.ajax({
                    type   : 'GET',
                    url    : '<?= Router::url(null, true) ?>weatherph/weatherph/getStations/meteomedia',
                    cache  : false,
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


                        //Gets other stations
                        mapStations($stationsOthers, stationIcon, 'others'); // now the stations are complete
                        mapStations($stations, meteomediaIcon, 'meteomedia'); // now the stations are complete

                        window['STATIONS'].meteomedia = $stations;

                        $('select[name=philippine-regions]')
                            .find('option[data-region-id=Philippines]')
                            .attr('selected','selected')
                            .change();
                    }
                });

            }
        });
    } else {
        mapStations(window['STATIONS'].meteomedia, meteomediaIcon, 'meteomedia');
        mapStations(window['STATIONS'].others, stationIcon, 'others');
    }
}

function getDataLayer(){

    var gemCodeForRegions = '';
    switch ($("select[name=philippine-regions] option:selected").attr('data-region-id')){
        case 'Philippines':
            gemCodeForRegions = 'all';
            break;
        case 'Luzon':
            gemCodeForRegions = 'luzon';
            break;
        case 'VisMin':
            gemCodeForRegions = 'visayas_mindanao';
            break;
        case 'Palawan':
            gemCodeForRegions = 'palawan_sulu';
            break;
    }

    // Available layers
    var dataLayers = window["DATA_LAYERS"];
    // The currently-selected layer
    var dataLayer = window["DATA_LAYER"];

    // console.error('x~>'+dataLayer);
    if (dataLayer == 'temperature' || dataLayer == 'pressure' || dataLayer == 'satellite') {
//        $('.data-layer').animate({
//            opacity: 0
//        }, 600, function(){
            $('.layer.slides_container').html('');
            /**
             * This is responsible for adding the layer images for animation
             */
            for (var key in window['fileNames'][dataLayer]) {
                var c = window['fileNames'][dataLayer][key];
                var _imageName = ""+c.year+c.month+c.day+c.hour+c.min+'00'+gemCodeForRegions+'_'+dataLayer;

                $('.layer.slides_container').append(
                    '<img src="<?= $this->webroot ?>theme/weatherph/img/layers/'+_imageName+'.png" '
                    +'data-year="'   + c.pst_year  + '" '
                    +'data-month="'  + c.pst_month + '" '
                    +'data-day="'    + c.pst_day   + '" '
                    +'data-hour="'   + c.pst_hour  + '" '
                    +'data-minute="' + c.pst_min   + '" '
                    +'data-second="00" '
                    +'style="display: none;"'
                    +' />'
                );
            }

            $('.data-layer').css('opacity', 0);
            $('.data-layer').animate({
                opacity: 1
            }, 1000, function(){
                /**
                * This is responsible for animating the added layer images
                */
                $('#layer-slides').slides({
                    preload: false,
                    effect: 'fade',
                    crossfade: true,
                    fadeSpeed: 1000,
                    play: 2000,
                    pagination: false,
                    generatePagination: false,
                    generateNextPrev: false,
                    animationStart: function(){
                        var $visible = $('.layer.slides_container img:visible');

                        if ($visible.length == 1) {
                            var minutes =  $visible.attr('data-minute');

                            minutes -= 2;
                            if(minutes === 0) minutes = '00';

                            $('.data-layer-label .timestamp .date .year').html($visible.attr('data-year'));
                            $('.data-layer-label .timestamp .date .month').html($visible.attr('data-month'));
                            $('.data-layer-label .timestamp .date .day').html($visible.attr('data-day'));

                            $('.data-layer-label .timestamp .time .hour').html($visible.attr('data-hour'));
                            $('.data-layer-label .timestamp .time .minute').html(minutes) ;
                            $('.data-layer-label .timestamp .time .second').html('00');
                        }
                    }
                });
            }); // animation callback
       // });
    }

}

function getObjects(obj, key, val) {
    var objects = [];
    for (var i in obj) {
        if (!obj.hasOwnProperty(i)) continue;
        if (typeof obj[i] == 'object') {
            objects = objects.concat(getObjects(obj[i], key, val));
        }else if(i ==key && obj[key] == val){
            objects.push(obj);
        }
    }
    return objects;
}

function redrawMap(){
    var dataLayer = window['DATA_LAYER'];
    var serviceName = 'stations';


    // console.error(dataLayer);
    if (dataLayer !== null) {
        getDataLayer();

        switch(dataLayer) {
            case 'temperature':
            case 'pressure':
            case 'satellite':
                $('.data-layer-label').show();
                $('#map').data('map').dragging.disable();
                $('#map').data('map').doubleClickZoom.disable();
                $('#map').data('map').touchZoom.disable();
                $('#map').data('map').scrollWheelZoom.disable();
                $('.data-layer').show();
                $('.leaflet-control-zoom').hide();
                break;
            default:
                $('.data-layer-label').hide();
                $('#map').data('map').dragging.enable();
                $('#map').data('map').doubleClickZoom.enable();
                $('#map').data('map').touchZoom.enable();
                $('#map').data('map').scrollWheelZoom.enable();
                $('.data-layer').hide();
                break;
        }

        switch (dataLayer) {
            case 'temperature':
                serviceName = 'temperature';
                $('.scale-celsius').show();
                $('.scale-fahrenheit').hide();
                $('.scale-temperature').show();
                $('.scale-pressure').hide();
                $('.minor-area').attr('disabled','true');

                $('#map').data('map').removeLayer(window['LEAFLET_TILES'].stations);
                $('#map').data('map').removeLayer(window['LEAFLET_TILES'].pressure);
                $('#map').data('map').removeLayer(window['STATIONS_LAYER']);
                $('#map').data('map').addLayer(window['LEAFLET_TILES'].temperature);
                break;

            case 'pressure':
                $('.scale-temperature').hide();
                $('.scale-pressure').show();
                $('.minor-area').attr('disabled','true');
                serviceName = 'pressure';

                $('#map').data('map').removeLayer(window['LEAFLET_TILES'].stations);
                $('#map').data('map').removeLayer(window['LEAFLET_TILES'].temperature);
                $('#map').data('map').removeLayer(window['STATIONS_LAYER']);
                $('#map').data('map').addLayer(window['LEAFLET_TILES'].pressure);
                break;

            case 'satellite':
                $('.scale-temperature').hide();
                $('.scale-pressure').hide();
                $('.minor-area').attr('disabled','true');
                serviceName = 'satellite';

                $('#map').data('map').removeLayer(window['LEAFLET_TILES'].stations);
                $('#map').data('map').removeLayer(window['LEAFLET_TILES'].temperature);
                $('#map').data('map').removeLayer(window['STATIONS_LAYER']);
                $('#map').data('map').addLayer(window['LEAFLET_TILES'].pressure);
                break;

            case 'stations':
                $('.data-layer-label').hide();
                $('.leaflet-control-zoom').show();
                $('.scale-temperature').hide();
                $('.scale-pressure').hide();
                $('.minor-area').removeAttr('disabled');
                remapStations();
                $('#map').data('map').removeLayer(window['LEAFLET_TILES'].temperature);
                $('#map').data('map').removeLayer(window['LEAFLET_TILES'].pressure);
                $('#map').data('map').addLayer(window['LEAFLET_TILES'].stations);
                $('#map').data('map').addLayer(window['STATIONS_LAYER']);
                break;
            default:
                $('.scale-temperature').hide();
                $('.data-layer-label').hide();
                break;
        }
        currentRegion = 'All';
        videoRegion = 'All';

        $('.province-select').find("option:selected").each(function(){
            $('select[name=philippine-regions]')
                .find('option[data-region-id=Philippines]')
                .attr('selected','selected')
                .change();
        });

    }
    $('.leaflet-container').css('background','transparent');
    $('.active-layer').removeClass('active-layer');
    $('[data-name = "'+dataLayer+'"]').addClass('active-layer');
}

function onionSkinMap() {
    var mapOpacity = 1;
    var dataLayer = window['DATA_LAYER'];

    if (dataLayer !== null) {
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

$(document).ready(function(){
    $('#video-viewport').hide();
    $('.data-layers a').on('click', function(evt){
        evt.preventDefault();

        var $video = $('.video-viewport');
        var $map = $('.map-viewport');

        _name = $(this).attr('data-name');
        region_stations = $('.active-layer').text();

        window["DATA_LAYER"] = _name;
        // console.error('Set~>'+window["DATA_LAYER"]);

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

        if ($(this).attr('data-type') == 'movie') {

        // This switches the region back to "All_Philippines" when the movies layer is selected.
            $('.province-select').find("option:selected").each(function(){
                $('.province-select').find("option:selected").removeProp('selected');
                $('select[name=philippine-regions]')
                    .find('option[data-region-id=Philippines]')
                    .attr('selected','selected')
                    .change();
            });

            $('.active-layer').removeClass('active-layer');
            $('#movie-layer').addClass('active-layer');
            $('.scale-temperature').hide();
            $('.scale-pressure').hide();
            $('.minor-area').attr('disabled','true');

            var $movie = $('#movie-'+_name); // The markup
            var content = eval("window['MOVIE_CONTENT']."+_name);

            $map.fadeOut(1000, function(){
                // console.error(eval("window['MOVIE_CONTENT']."+_name));
                $video
                .html(eval("window['MOVIE_CONTENT']."+_name));
                // console.error($video);
                $video
                .fadeIn(1000)
                .find('video').show();
            //            jwplayer("#window['MOVIE_CONTENT']."+_name).setup({
            //                flashplayer: "<? $this->webroot . 'weatherph/swf/player.swf'?>"
            //            });
            });

        } else {
            // console.error('is not movie');
            var videoVisible = $video.is(':visible');
            var mapVisible = $map.is(':visible');
            if (videoVisible) {
                // console.error('video visible');
                $video.fadeOut(1000, function(){
                    $map.fadeIn(1000);
                    redrawMap();
                });
            } else {
                var opaque = {
                    opacity: 1
                };
                var transparent = {
                    opacity: 0.5
                };
                $('#map').animate(transparent, 600, function(){
                    redrawMap();
                    $('#map').animate(opaque, 600, function(){
                        onionSkinMap();
                    });
                });

                if (mapVisible) {
                    // console.error('is visible');
                } else {
                    // console.error('is invisible');
                }
            }
        }
    });
});