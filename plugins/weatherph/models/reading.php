<?php

class Reading extends WeatherphAppModel {

    public $name = 'Reading';
    
    public $belongsTo = array(
        'Station' => array(
            'className'    => 'Station',
            'foreignKey'    => 'station_id'
        )
   );
}
