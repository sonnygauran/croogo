$(document).ready(function(){
    $(".search-results ul li").click(function(){
    window.location=$(this).find("a").attr("href"); return false;
    });
    
    $(document).on('mouseover mouseout', 'div.plot', function(){
        var attributes = $(this).attr('class').split(' ');
        var id = attributes[1];
        console.error(id);
        if (event.type == 'mouseover') {
            $('li.' + id).css('background-color', '#e0e6f2');
        } else {
            $('li.' + id).css('background-color', 'transparent');
        }
        
    });
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
    var keyword = pathname[2];
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
        
        }
    
    });
    
    $('.marker').hide();
    
    $('.location').hover(function(event){
        event.preventDefault();
        attributes = $(this).attr('class').split(" ");
        id = attributes[0];
        
        $details = getObjects($locationResults, 'id', id);
        $('#plot-' + $details[0].id).addClass('marker').removeClass('plot');
    }, function(){
        $('#plot-' + $details[0].id).addClass('plot').removeClass('marker');
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
            }, '<div class="plot ' + $currentStation.id + '" id="plot-' + $currentStation.id + '"></div>',true);
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
    
    var $boxMap = [
        //MAJOR AREAS
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
});