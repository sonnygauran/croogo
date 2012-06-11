<?php
    CroogoRouter::connect('/', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'index'));
    CroogoRouter::connect('/getStations', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getStations'));
    CroogoRouter::connect('/view', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'view'));
    CroogoRouter::connect('/view/*', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'view'));
    
    CroogoRouter::connect('/typhoon-preparedness', array('plugin' => 'weatherph', 'controller' => 'typhoon', 'action' => 'preparedness'));
    CroogoRouter::connect('/typhoon-climatology', array('plugin' => 'weatherph', 'controller' => 'typhoon', 'action' => 'climatology'));
    CroogoRouter::connect('/typhoon-glossary', array('plugin' => 'weatherph', 'controller' => 'typhoon', 'action' => 'glossary'));
    
    CroogoRouter::connect('/names', array('plugin' => 'weatherph', 'controller' => 'names', 'action' => 'index'));
    
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
    
    
