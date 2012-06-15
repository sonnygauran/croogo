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
    
    
    $locationResults = new Array();
    var pathname = window.location.pathname.split('/');
    var keyword = pathname[3];
    alert('<?= $this->webroot ?>getResultCoordinates/' + keyword);
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
                    name: $currentRetrievedStation.Name.full_name_ro,
                    type:'Point',
                    coordinates: [
                    $currentRetrievedStation.Name.long,
                    $currentRetrievedStation.Name.lat
                    ]
                });
            }
        
            plotLocations($locationResults); // now the stations are complete
        
        }
    
    
    });
    
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
                strokeWidth: "1px", 
                height: "6px", 
                width: "6px", 
                radius: "8px", 
                color: "#dd2222", 
                fillOpacity: "0", 
                strokeOpacity: "1"
            },true);
        }
    }
});