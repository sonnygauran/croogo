<?php

class Reading extends WeatherphAppModel {

    public $name = 'Reading';
    
    public $hasOne = array(
        'Warning' => array(
            'className'  => 'Warning',
            'foreignKey' => 'reading_id',
        )
    );

}
