<?php

/**
 * @author Jaggy Gauran
 *  
 * Meteomedia Plugin - Any Chart 
 * 
 */
class AnyChart {

    
    /**
     * Dynamic objects depending on given parameters
     * 
     */
    
    private static $data = array(
        'minor_interval' => '',
        'show_cross_label' => '',
        'default_series_type' => '',
    );
    
    /**
     *Global properties.. 
     * 
     * @var type 
     */
    private static $properties = array(
        'chart_type' => '',
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
        ),
        'margin' => array(
            'properties' => array(
                'all' => 3,
                'botton' => 0,
                'left' => 10,
                'right' => 8,
            ),
        ),
    );
    
    private static function globalSettings(){
        $settings =  array(
            'properties' => array(),
            'children' => array(
                'margin' => self::$properties['margin'],
                'locale' => array(
                    'children'=> array(
                        'date_time_format' => array(
                            'properties' => array(),
                            'children' => array(
                                'format' => array(
                                    'properties' => array(),
                                    'value' => '%u',
                                )
                            ),
                        ),
                        'week_days'=> array(
                            'properties' => array(),
                            'children' => array(
                                'short_names' => array(
                                    'properties' => array(
                                        'start_from' => 'Sunday'
                                    ),
                                    'value' => '<![CDATA[ Su.,Mo.,Tu.,We.,Th.,Fr.,Sa. ]]>',
                                )
                            ),
                        )
                    ),

                ),
            )
        );
        
        switch(self::$properties['chart_type']){
            case 'precip':
            case 'precipitation':
                $settings['children']['months'] = array(
                        'properties' => array(),
                        'children' => array(
                            'names' => array(
                                'properties'=> array(),
                                'value' => 'January,February,March,April,May,June,July,August,September,October,November,December'
                            ),
                            'short_names' => array(
                                'properties'=> array(),
                                'value' => 'Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec'
                            ),
                        ),
                );
                break;
        }
        
        return $settings;
    }
    
    private static function chartSettings(){
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
                            'minor_interval'=>  self::$data['minor_interval'],
                            'minor_interval_unit'=>'Hour',
                            'major_interval'=>'1',
                            'major_interval_unit'=>'Day',
                        ),
                        'value' => null
                    ),
                    'title' => array(
                        'properties' => array(
                            'enabled' => (empty(self::$properties['titles']['x_axis'])) ? 'false' : 'true'
                        ),
                        'value' => self::$properties['titles']['x_axis'],
                    ),
                    'labels' => array(
                        'properties' => array(
                            'enabled' => 'true',
                            'show_cross_label' => self::$data['show_cross_label'],
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
                                'properties' => self::$properties['font'],
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
        
        $y_axis = array(
            'properties' => array(),
            'children' => array(
                'title' => array(
                    'properties' => array(
                        'enabled' => (empty(self::$properties['titles']['y_axis'])) ? 'false' : 'true'
                    ),
                    'value' => self::$properties['titles']['y_axis'],
                ),
                'labels' => array(
                    'properties' => array(),
                    'children' => array(
                        'format' => array(
                            'properties' => array(),
                            'value' => '{%Value}{numDecimals:0}'
                        ),
                        'font' => array(
                            'properties' => self::$properties['font'],
                            'value' => null,
                        ),
                    ),
                ),
            ),
        );
        switch(self::$properties['chart_type']){
            case 'winddir':
            case 'dir':
            case 'wind_direction':
                $y_axis['children']['scale'] = array(
                    'properties' => array(
                        'type' => 'Linear',
                        'maximum' => 100,
                        'minimum' => 0,
                    ),
                );
                break;
            case 'humidity':
                $y_axis['children']['scale'] = array(
                    'properties' => array(
                        'type' => 'Linear',
                        'maximum' => 1,
                        'minimum' => 0,
                        'maximum_offset' => 0.01,
                        'minimum_offset' => 0.01,
                    ),
                    
                );
                break;
        }
        
        $chart_settings = array(
            'properties' => array(),

            'children' => array(
                'title' => array(
                    'properties' => array(
                        'enabled' => (empty(self::$properties['titles']['chart'])) ? 'false' : 'true'
                    ),
                    'value' => self::$properties['titles']['chart'],
                ),
                'axes' => array(
                    'properties' => array(),
                    'children' => array(
                        'x_axis' => $x_axis,
                        'y_axis' => $y_axis,
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
        );
        
        return $chart_settings;
    }
    
    public static function createChart($type, $arrData){
//        CakeLog::write('anychart', print_r($arrData, true));
        
        self::$properties['chart_type'] = $type;
        self::$data .= $arrData['settings'];
        
        $anychart = array(
            'anychart' => array(
                'children' => array(
                    'settings' => self::globalSettings(),
                    'chart_settings' => self::chartSettings()
                )
            )
        );
        
        CakeLog::write('anychart', print_r($anychart, true));
        
        return $anychart;
    }
}