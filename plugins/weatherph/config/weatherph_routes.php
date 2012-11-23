<?php
    CroogoRouter::connect('/admin/stations', array('plugin' => 'weatherph', 'controller' => 'stations', 'action' => 'index', 'admin' => true));
    CroogoRouter::connect('/admin/stations/update', array('plugin' => 'weatherph', 'controller' => 'stations', 'action' => 'update', 'admin' => true));
    CroogoRouter::connect('/', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'index'));
    CroogoRouter::connect('/getStations', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getStations'));
    CroogoRouter::connect('/view', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'view'));
    CroogoRouter::connect('/view/*', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'view'));

    CroogoRouter::connect('/typhoon/preparedness', array('plugin' => 'weatherph', 'controller' => 'typhoon', 'action' => 'preparedness'));
    CroogoRouter::connect('/typhoon/climatology', array('plugin' => 'weatherph', 'controller' => 'typhoon', 'action' => 'climatology'));
 // CroogoRouter::connect('/typhoon-glossary', array('plugin' => 'weatherph', 'controller' => 'typhoon', 'action' => 'glossary'));

    CroogoRouter::connect('/:city-:id', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getDmoForecast'), array(
        'pass' => array('id','city'),
        'id'   => '[0-9]+',
        'city' => '[A-Za-z_]+',
    ));

    CroogoRouter::connect('/names', array('plugin' => 'weatherph', 'controller' => 'names', 'action' => 'index'));
    CroogoRouter::connect('/search', array('plugin' => 'weatherph', 'controller' => 'search', 'action' => 'index'));
    CroogoRouter::connect('/getResultCoordinates/*', array('plugin' => 'weatherph', 'controller' => 'search', 'action' => 'getResultCoordinates'));
    CroogoRouter::connect('/results/*', array('plugin' => 'weatherph', 'controller' => 'search', 'action' => 'index'));

    CroogoRouter::connect('/founders/meteomedia', array('plugin' => 'weatherph', 'controller' => 'founders', 'action' => 'meteomedia'));
    CroogoRouter::connect('/founders/aboitiz', array('plugin' => 'weatherph', 'controller' => 'founders', 'action' => 'aboitiz'));
    CroogoRouter::connect('/founders/unionbank', array('plugin' => 'weatherph', 'controller' => 'founders', 'action' => 'unionbank'));
    CroogoRouter::connect('/founders/about', array('plugin' => 'weatherph', 'controller' => 'founders', 'action' => 'about'));

    CroogoRouter::connect('/dictionaries/filipino', array('plugin' => 'weatherph', 'controller' => 'dictionaries', 'action' => 'filipino'));
    CroogoRouter::connect('/dictionaries/english', array('plugin' => 'weatherph', 'controller' => 'dictionaries', 'action' => 'english'));
//    CroogoRouter::connect('/impressum', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'impressum'));

    CroogoRouter::connect('/getForecast', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getForecast'));
    CroogoRouter::connect('/getForecast/*', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getForecast'));
    CroogoRouter::connect('/getDetailedForecast', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getDetailedForecast'));
    CroogoRouter::connect('/getDetailedForecast/*', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getDetailedForecast'));
    CroogoRouter::connect('/detailedForecast', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'detailedForecast'));
    CroogoRouter::connect('/detailedForecast/*', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'detailedForecast'));

    CroogoRouter::connect('/getDetailedReading', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getDetailedReading'));
    CroogoRouter::connect('/getDetailedReading/*', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getDetailedReading'));
    CroogoRouter::connect('/detailedReading', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'detailedReading'));
    CroogoRouter::connect('/detailedReading/*', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'detailedReading'));
    CroogoRouter::connect('/dmoForecast/*', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getDmoForecast'));

    CroogoRouter::connect('/weathertv/*', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'weathertv'));
    CroogoRouter::connect('/webcam', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'webcam'));
    CroogoRouter::connect('/news', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'news'));
    CroogoRouter::connect('/news/payong-panahon', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'payongpanahon'));
    CroogoRouter::connect('/news/mata-ng-bagyo', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'matangbagyo'));
   
    CroogoRouter::connect('/media/view/*', array( 'controller' => 'media', 'action' => 'view'));
   