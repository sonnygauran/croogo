<?php

/**
 * @author Jaggy Gauran
 *  
 * Meteomedia Plugin - Any Chart 
 * 
 */
class AnyChart {

    private static $instance;
    
    private $scale = 'XXXXXXXXXXXXXXXXXX';
    private $minorInterval = 'XXXXXXXXXXXXXXXXXX';
    private $showCrossLabel = 'XXXXXXXXXXXXXXXXXX';
    
    private $properties = array(
        'font' => array(
            'family' => 'Helvetica',
            'color' => '#444444',
            'size' => '11',
        ),
        // Place null for empty titles
        'titles' => array(
            'chart' => null, 
            'x_axis' => null,
            'y_axis' => null,
        )
    );
    
    
    private $margin = array(
        'margin' => array(
            'properties' => array(
                'all' => 3,
                'botton' => 0,
                'left' => 10,
                'right' => 8,
            ),
            'values' => null,
            
        )
    );
    
    private $settings =  array(
            'settings' => array(
                'children' => array(

                    'locale' => array(
                        'children'=> array(

                            'date_time_format' => array(
                                'value' => 'safasd'
                            ),
                        )
                    )
                )
            )
        );
    
    
    
    public function __construct() {
        
    }

    public static function getInstance() {
        if (empty(self::$instance))
            self::$instance = new AnyChart();
        return self::$instance;
    }

    public function chartSettings(){
        $x_axis = array(
                'properties' => array(
                    'enable' => 'true'
                ),
                'children' => array(
                    'scale' => array(
                        'properties' => array(
                            'type'=>'DateTime',
                            'minimum_offset'=>'0',
                            'maximum_offset'=>'0',
                            'minor_interval'=>  $this->minorInterval,
                            'minor_interval_unit'=>'Hour',
                            'major_interval'=>'1',
                            'major_interval_unit'=>'Day',
                        ),
                        'value' => null
                    ),
                    'title' => array(
                        'properties' => array(
                            'enabled' => (empty($this->properties['titles']['x_axis'])) ? 'false' : 'true'
                        ),
                        'value' => $this->properties['titles']['x_axis'],
                    ),
                    'labels' => array(
                        'properties' => array(
                            'enabled' => 'true',
                            'show_cross_label' => $this->showCrossLabel,
                            'allow_overlap'=>'true'
                        ),
                        'children' => array(
                            'format' => array(
                                'properties' => array(),
                                'value' => array(
                                    '<![CDATA[ ]]>',
                                    '{%Value}{dateTimeFormat:%ddd %dd.%MM.}'
                                ),
                            ),
                            'font' => array(
                                'properties' => $this->properties['font'],
                                'value' => null,
                            ),

                        ),
                    ),
                    'major_grid' => array(
                        'properties' => array(
                            'enabled' => 'true',
                            'interlaced' => 'true',
                        ),
                        'children' => array(
                            'interlaced_fills' => array(
                                'properties' => array(),    
                                'children' => array(
                                    'even' => array(
                                        'properties' => array(),
                                        'children' => array(
                                            'fill' => array(
                                                'properties' => array(
                                                    'color' => 'rgb(245,245,245)',
                                                    'opacity' => '1'
                                                ),
                                                'value' => null
                                            )
                                        )
                                    )
                                ),
                            )
                        ),
                    ),
                ),
        );
        $chart_settings = array(
            'chart_settings' => array(
                'properties' => array(),

                'children' => array(
                    'title' => array(
                        'properties' => array(
                            'enabled' => (empty($this->properties['titles']['chart'])) ? 'false' : 'true'
                        ),
                        'value' => $this->properties['titles']['chart'],
                    ),
                    'axes' => array(
                        'properties' => array(),
                        'children' => array(
                            'x_axis' => $x_axis,
                            'y_axis' => array(
                                'properties' => array(),
                                'children' => array(

                                ),
                            ),
                            'extra' => array(
                                'properties' => array(),
                                'children' => array(

                                ),
                            ),
                            'chart_background' => array(
                                'properties' => array(),
                                'children' => array(

                                ),
                            ),
                            'data_plot_background' => array(
                                'properties' => array(),
                                'children' => array(

                                ),
                            ),
                        ),

                    ),
                )
            )
        );
        
        return $chart_settings;
    }
}