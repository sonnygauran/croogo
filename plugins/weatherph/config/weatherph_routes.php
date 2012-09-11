<?php
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
    
    CroogoRouter::connect('/weathertv', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'weathertv'));
    CroogoRouter::connect('/webcam', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'webcam'));
    CroogoRouter::connect('/news', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'payongpanahon'));
    CroogoRouter::connect('/announcements', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'matangbagyo'));

    //Tourism ruotes
    CroogoRouter::connect('/alaminos', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'alaminos'));
    CroogoRouter::connect('/angeles', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'angeles'));
    CroogoRouter::connect('/antipolo', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'antipolo'));
    CroogoRouter::connect('/baguio', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'baguio'));
    CroogoRouter::connect('/bais', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'bais'));
    CroogoRouter::connect('/balanga', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'balanga'));
    CroogoRouter::connect('/batangas', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'batangas'));
    CroogoRouter::connect('/bayawan', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'bayawan'));
    CroogoRouter::connect('/cadiz', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'cadiz'));
    CroogoRouter::connect('/cagayan_de_oro', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'cagayan_de_oro'));
    CroogoRouter::connect('/calamba', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'calamba'));
    CroogoRouter::connect('/caloocan', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'caloocan'));
    CroogoRouter::connect('/cauayan', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'cauayan'));
    CroogoRouter::connect('/cavite', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'cavite'));
    CroogoRouter::connect('/dagupan', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'dagupan'));
    CroogoRouter::connect('/davao', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'davao'));
    CroogoRouter::connect('/digos', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'digos'));
    CroogoRouter::connect('/dipolog', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'dipolog'));
    CroogoRouter::connect('/gapan', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'gapan'));
    CroogoRouter::connect('/iligan', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'iligan'));
    CroogoRouter::connect('/iloilo', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'iloilo'));
    CroogoRouter::connect('/iriga', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'iriga'));
    CroogoRouter::connect('/isabela', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'isabela'));
    CroogoRouter::connect('/kabankalan', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'kabankalan'));
    CroogoRouter::connect('/kidapawan', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'kidapawan'));
    CroogoRouter::connect('/koronadal', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'koronadal'));
    CroogoRouter::connect('/laoag', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'laoag'));
    CroogoRouter::connect('/ligao', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'ligao'));
    CroogoRouter::connect('/lipa', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'lipa'));
    CroogoRouter::connect('/lucena', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'lucena'));
    CroogoRouter::connect('/makati', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'makati'));
    CroogoRouter::connect('/malaybalay', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'malaybalay'));
    CroogoRouter::connect('/mandaue', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'mandaue'));
    CroogoRouter::connect('/masbate', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'masbate'));
    CroogoRouter::connect('/muntinlupa', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'muntinlupa'));
    CroogoRouter::connect('/olongapo', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'olongapo'));
    CroogoRouter::connect('/pasay', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'pasay'));
    CroogoRouter::connect('/puerto_princesa', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'puerto_princesa'));
    CroogoRouter::connect('/samal', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'samal'));
    CroogoRouter::connect('/san_fernando', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'san_fernando'));
    CroogoRouter::connect('/san_fernando_la_union', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'san_fernando_la_union'));
    CroogoRouter::connect('/santa_rosa', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'santa_rosa'));
    CroogoRouter::connect('/santiago', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'santiago'));
    CroogoRouter::connect('/silay', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'silay'));
    CroogoRouter::connect('/sorsogon', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'sorsogon'));
    CroogoRouter::connect('/tacurong', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'tacurong'));
    CroogoRouter::connect('/tagum', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'tagum'));
    CroogoRouter::connect('/tangub', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'tangub'));
    CroogoRouter::connect('/toledo', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'toledo'));
    CroogoRouter::connect('/tuguegarao', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'tuguegarao'));
    CroogoRouter::connect('/urdaneta', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'urdaneta'));
    CroogoRouter::connect('/valenzuela', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'valenzuela'));
    CroogoRouter::connect('/victorias', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'victorias'));
    CroogoRouter::connect('/vigan', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'vigan'));
    CroogoRouter::connect('/zamboanga', array('plugin' => 'weatherph', 'controller' => 'tourism', 'action' => 'zamboanga'));
    
    