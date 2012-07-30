var $boxMap = [

//MAJOR AREAS
//These values were just taken directly from wetter4. No conversion.
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
    box: [118.18475000000187,5.729469014423421,132.24724999999813,13.607339308212687]
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
},

];

$(document).ready(function(){
    window['LEAFLET_TILES_SRC'] = {
        stations:    "<?= Configure::read('Tile.main.url') ?>/{z}/{x}/{y}.png"
    }
    window['LEAFLET_TILES'] = {
        stations:    {}
    }

    window['ATTRIBUTION'] = '';
    window['STATIONS_LAYER'] = new L.LayerGroup();
    
    var map = new L.Map('map', {
        maxZoom: 10,
        minZoom: 5,
        zoom: 7,
        layers: [window['STATIONS_LAYER']],
        zoomControl: false
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

    map
    .setView(new L.LatLng(14.5167, 121), 7)
    .addLayer(window['LEAFLET_TILES'].stations)
    .setMaxBounds(bounds);
    map.panTo(bounds.getCenter()).setZoom(7);
    
    var baseMaps = {
        "Stations": window['LEAFLET_TILES'].stations
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


    $stationsPagasa = new Array();
    window['STATIONS'] = {
        pagasa: null,
        meteomedia: null
    };

    remapStations();

    $(window).load(function() {
        setTimeout(function() {
            $('select[name=philippine-regions]').change(function(){
                $("select[name=philippine-regions] option:selected").each(function () {

                    if ($(this).attr('selected')) { // Is the current <option> selected?
                        $region = $(this).attr('data-region-id'); // the region id

                        for (var key in $boxMap) { // let's traverse the $boxMap
                            if ($boxMap[key].id == $region) {  // Initially matches 'data-region-id' with 'NCR'
                                $current = $boxMap[key].box; // the current $boxMap record
                                console.error($boxMap[key]);
                                var southWest = new L.LatLng($current[1],$current[0]),
                                northEast = new L.LatLng($current[3],$current[2]),
                                bounds    = new L.LatLngBounds(southWest, northEast);
                                console.error();
                                
                                var map = $('#map').data('map');
                                
                                var zoom = 7;
                                if ($boxMap[key].id == 'Philippines') {
                                    zoom = 4;
                                }
                                if ($(this).parent('optgroup').hasClass('minor-area')) {
                                    zoom = 8;
                                }
                                
                                map.panTo(bounds.getCenter()).setZoom(zoom);
                                //map.panInsideBounds(bounds);
                                console.log($current);
                            }
                        }
                    } // END IF
                });
            });
        }, 300);
    });
});

function isiPhone(){
    return (
        //Detect iPhone
        (navigator.platform.indexOf("iPhone") != -1) ||
        //Detect iPod
        (navigator.platform.indexOf("iPod") != -1)
        );
}

var StationIconWeb = L.Icon.extend({
    iconUrl: '<?= Router::url(null, true) ?>theme/weatherph/img/leaflet/marker-icon-red-small-transparent.png',
    shadowUrl: '<?= Router::url(null, true) ?>/theme/weatherph/img/leaflet/marker-shadow.png',
    iconSize: new L.Point(8, 13),
    shadowSize: new L.Point(13, 13),
    iconAnchor: new L.Point(8, 13),
    popupAnchor: new L.Point(-5, -20)
});

var StationIconMobile = L.Icon.extend({
    iconUrl: '<?= Router::url(null, true) ?>theme/weatherph/img/leaflet/marker-icon-red-small-transparent.png',
    shadowUrl: '<?= Router::url(null, true) ?>/theme/weatherph/img/leaflet/marker-shadow.png',
    iconSize: new L.Point(12, 20),
    shadowSize: new L.Point(20, 20),
    iconAnchor: new L.Point(12, 20),
    popupAnchor: new L.Point(-5, -20)
});


var stationIcon    = new StationIconWeb();
var meteomediaIcon = new StationIconWeb('<?= Router::url(null, true) ?>theme/weatherph/img/leaflet/marker-icon-blue-small.png');

if (isiPhone() || (navigator.userAgent.match(/iPad/i) != null)) {
    stationIcon    = new StationIconMobile();
    meteomediaIcon = new StationIconMobile('<?= Router::url(null, true) ?>theme/weatherph/img/leaflet/marker-icon-blue-small.png');
}

function mapStationsPagasa($stationsArray) {
    mapStations($stationsArray, stationIcon);
}

function mapStations($stationsArray, icon) {
    console.error(icon);
    
    var _icon = meteomediaIcon;
    if (icon != null) {
        _icon = icon;
    }
    
    // This loop maps the stations from the $stations fetched from getStations
    var counter = 0;
    var isiPad = navigator.userAgent.match(/iPad/i) != null;
            
    for (var key in $stationsArray) {
        
        $currentStation = $stationsArray[key];
        var markerLocation = new L.LatLng($currentStation.coordinates[1], $currentStation.coordinates[0]);
        var marker = new L.Marker(markerLocation, {
            icon: _icon
        });
        
        var content = "<b>"+$currentStation.name+"</b>";
        //        if (isiPad || isiPhone()) {
        content += "<br /><a href=\"#\" class=\"marker-popup\" data-id=\""+$currentStation.id+" \">View Details</a>";
        //        }
        marker.bindPopup(content);

        marker.station = {
            id: $currentStation.id,
            name: $currentStation.name
        }
        
        marker
        .on('click', function(e){
            var self = this;
//            if (isiPhone() || isiPad) {
            $('.marker-popup').on('click', function(evt){
                evt.preventDefault();
                window.location = "<?= Router::url(null, true) ?>dmoForecast/" + self.station.id;
                self.closePopup();
            });
        });
        window['STATIONS_LAYER'].addLayer(marker);
    }
    $('#map').data('map').addLayer(window['STATIONS_LAYER']);
}

function remapStations() {
    var url = window.location.pathname;
    url = url.split('/');
    var keyword = url[url.length - 1];

    if (window['STATIONS'].pagasa == null) {
        $.ajax({
            type   : 'GET',
            url    : '<?= Router::url(null, true) ?>getResultCoordinates/' + keyword,
            cache  : false,
            success: function(data) {
                var $retrievedStations = data; // the complete retrieved stations
                for (var key in $retrievedStations) {
                    var $currentRetrievedStation = $retrievedStations[key]; // current station on the loop
                    //                    console.log($currentRetrievedStation);
                    $stationsPagasa.push({ // create a json object, and then save it to stations array
                        id: $currentRetrievedStation.Name.id,
                        name: $currentRetrievedStation.Name.full_name_ro,
                        type:'Point',
                        coordinates: [
                        $currentRetrievedStation.Name.long,
                        $currentRetrievedStation.Name.lat
                        ]
                    });
                }
                // Temporary Hidden
                window['STATIONS'].pagasa = $stationsPagasa;

                //Gets all the stations from pagasa
                mapStations($stationsPagasa, stationIcon); // now the stations are complete
            }
        });
    } else {
        mapStations(window['STATIONS'].pagasa, stationIcon);
    }
}