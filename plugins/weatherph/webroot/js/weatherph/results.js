$(document).ready(function(){
    var map = $("#map").geomap({
        center: [ 121.750488, 12.698865],
        // [123.5, 12.902712695115516]
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
    
    
    $locationResults = new Array();
    var pathname = window.location.pathname.split('/');
    var keyword = pathname[3];
    //alert('<?= $this->webroot ?>getResultCoordinates/' + keyword);
    $.ajax({
        type:     'POST',
        url :     '<?= $this->webroot ?>getResultCoordinates/' + keyword,
        cache:    false,
        success: function(data) {
            var $retrievedStations = data; // the complete retrieved stations
            for (var key in $retrievedStations) {
                var $currentRetrievedStation = $retrievedStations[key]; // current station on the loop
                //console.log($currentRetrievedStation);
                $locationResults.push({ // create a json object, and then save it to stations array
                    id: $currentRetrievedStation.Name.id,
                    name: $currentRetrievedStation.Name.full_name_ro,
                    type:'Point',
                    coordinates: [
                    $currentRetrievedStation.Name.long,
                    $currentRetrievedStation.Name.lat
                    ]
                });
            }
        
            plotLocations($locationResults); // now the stations are complete
            plotMarkers($locationResults); // now the stations are complete
        
        }
    
    });
    
    $('.marker').hide();
    
    $('.location').hover(function(event){
        event.preventDefault();
        id = $(this).attr('id');
        
        $details = getObjects($locationResults, 'id', id);
        $('#marker-' + $details[0].id).show();
    }, function(){
        
        $('#marker-' + $details[0].id).hide();
    }
    );
    
    function plotLocations($stationsArray) {
        // This loop maps the stations from the $stations fetched from getStations
        //console.log($stationsArray);
        for (var key in $stationsArray) {
            $currentStation = $stationsArray[key];
            $('#map').geomap("append", {
                name: $currentStation.name,
                type:'Point',                
                coordinates: $currentStation.coordinates
            }, {
                height : "0",
                width : "0"
            }, '<div class="plot" id="plot-' + $currentStation.id + '"></div>',true);
        }
    }
    
    function plotMarkers($stationsArray) {
        // This loop maps the stations from the $stations fetched from getStations
        //console.log($stationsArray);
        for (var key in $stationsArray) {
            $currentStation = $stationsArray[key];
            $('#map').geomap("append", {
                name: $currentStation.name,
                type:'Point',                
                coordinates: $currentStation.coordinates
            }, {
                height : "0",
                width : "0"
            }, '<div class="marker" style="display:none" id="marker-' + $currentStation.id + '"></div>',true);
        }
    }
    
    function getObjects(obj, key, val) {
        var objects = [];
        for (var i in obj) {
            if (!obj.hasOwnProperty(i)) continue;
            if (typeof obj[i] == 'object') {
                objects = objects.concat(getObjects(obj[i], key, val));
            } else if (i == key && obj[key] == val) {
                objects.push(obj);
            }
        }
        return objects;
    }
    
   
});