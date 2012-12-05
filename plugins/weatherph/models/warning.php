<?php

class Warning extends WeatherphAppModel {

    public $name = 'Warning';
    
    public $belongsTo = array(
        'Reading' => array(
            'className'    => 'Reading',
            'foreignKey'    => 'reading_id'
        )
   );
    
}
