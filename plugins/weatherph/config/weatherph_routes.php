<?php
    CroogoRouter::connect('/', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'index'));
    CroogoRouter::connect('/getStations', array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'getStations'));

