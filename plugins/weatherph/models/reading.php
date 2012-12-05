<?php

class Reading extends WeatherphAppModel {

    public $name = 'Reading';
    
    public $hasOne = array(
        'Warning' => array(
            'className'  => 'Warning',
            'foreignKey' => 'reading_id',
        )
    );

    public $belongsTo = array(
        'Station' => array(
            'className'    => 'Station',
            'foreignKey'    => 'station_id'
        )
   );
}
