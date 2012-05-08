<?php
    CroogoRouter::connect('/', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'index'));
    CroogoRouter::connect('/getStations', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getStations'));
    CroogoRouter::connect('/view', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'view'));
    CroogoRouter::connect('/view/*', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'view'));
    CroogoRouter::connect('/typhoonPreparedness', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'typhoonPreparedness'));
    CroogoRouter::connect('/typhoonClimatology', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'typhoonClimatology'));
    CroogoRouter::connect('/typhoonGlossary', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'typhoonGlossary'));
    CroogoRouter::connect('/about', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'about'));
    CroogoRouter::connect('/impressum', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'impressum'));
    
    CroogoRouter::connect('/getDetailedForecast', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getDetailedForecast'));
    CroogoRouter::connect('/getDetailedForecast/*', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getDetailedForecast'));
    CroogoRouter::connect('/detailedForecast', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'detailedForecast'));
    
    
    
