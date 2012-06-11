<?php

App::import('Lib', 'Meteomedia.Xml');

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
        'test' => 'sadf',
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
            'y_axis2' => null,
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
        $x_axis = array();
        $y_axis = array();
        $extra = array();
        $chart_background = array();
        $data_plot_background = array();
        
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

        
        $extra = array(
            'properties' => array(),
            'children' => array(
                'y_axis' => array(
                    'properties' => array(),
                    'children' => array(
                        'title' => array(
                            'properties' => array(
                                'enabled' => (empty(self::$properties['titles']['y_axis2'])) ? 'false' : 'true'
                            ),
                            'value' => self::$properties['titles']['y_axis2'],
                        ),
                        'labels' => array(
                            'properties' => array(),
                            'children' => array(
                                'format' => array()
                            ),
                        ),
                    ),
                )
            ),
        );

        $chart_background = array(
            'properties' => array(
                'enabled' => 'false'
            ),
            'children' => array(
                'effects' => array(
                    'properties' => array(
                        'enabled' => 'false'
                    ),
                    'value' => null,
                ),
                'boder' => array(
                    'properties' => array(
                        'enabled' => 'false'
                    ),
                    'value' => null,
                ),
                'inside_margin' => array(
                    'properties' => array(
                        'all' => '0' 
                    ),
                    'value' => null,
                ),
            ),
        );
        
        $data_plot_background = array(
            'properties' => array(),
            'children' => array(
                'effects' => array(
                    'properties' => array(
                        'enabled' => 'false'
                    ),
                    'value' => null,
                ),
            ),
        );
        
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
                        'extra' => $extra
                    ),

                ),
                'chart_background' => $chart_background,
                'data_plot_background' => $data_plot_background,
            )
        );
        
        return $chart_settings;
    }
    
    private static function dataPlotSettings(){
        
        $data_plot_settings = array();
        $graph_type = '';
        $graph_settings = array();
        $keys = array();
        
        $fill = array(
            'properties' => array(
                'enabled' => 'true',
                'type' => 'Gradient',
            ),
            'children' => array(
                'gradient' => array(
                    'properties' => array(
                        'type' => 'Radial'
                    ),
                    'children' => array(
                        'key' => array(
                            'properties' => array(),
                            'value' => array(),
                        ),
                        'key' => array(
                            'properties' => array(),
                            'value' => array(),
                        ),
                    )
                ),
                'border' => array(
                    'properties' => array(),
                    'value' => array(),
                ),
                'effects' => array(
                    'properties' => array(),
                    'value' => array(),
                ),
            ),
        );
        
        
        $bar_settings = array(
            'properties' => array(
                'point_padding' => '0',
                'scatter_point_width' => '4.7%',
            ),
            'children' => array(
                'bar_style' => array(
                    'properties' => array(),
                    'children' => array(
                        'fill' => array(
                            'properties' => array(
                                'enabled' => 'true',
                                'type' => 'Gradient',
                            ),
                            'children' => null
                        ),
                        'border' => array(
                            'properties' => array(
                                'enabled' => 'false',
                            ),
                            'value' => null
                        ),
                        'effects' => array(
                            'properties' => array(
                                'enabled' => 'false',
                            ),
                            'value' => null
                        ),
                    ),
                )
            ),
        );
        $line_settings = array(
            'properties' => array(),
            'children' => array(
                'marker_settings' => array(
                    'properties' => array(
                        'enabled' => 'false'
                    ),
                    'value' => null,
                ),
                'line_style' => array(
                    'properties' => array(),
                    'children' => array(
                        'line' => array(
                            'properties' => array(
                                'enabled' => 'true',
                                'thickness' => '2',
                                'caps' => 'round',
                                'joints' => 'round',
                            )
                        )
                    ),
                ),
            ),
        );
        
        
        
        
        switch(self::$properties['chart_type']){
            case 'winddir':
            case 'direction':
            case 'dir':
                $line_settings['children']['marker_settings']['properties']['enabled'] = 'true';
                break;
            case 'temp':
            case 'temperature':
                $line_settings['children']['tooltip_settings'] = array(
                    'properties'=> array(),
                    'children'=> array(
                        'format' => array(
                            'properties' => array(
                                'enabled'=>'true'
                            ),
                            'value' => '<![CDATA[ {%YValue}{numDecimals:1} ]]>',
                        )
                    ),
                );
                break;
            case 'sunshine':
               $keys = array(
                    Xml::createTag('key', array('position' => '0','color' => '#FFD500')),
                    Xml::createTag('key', array('position' => '0.3','color' => '#FFF000')),
                    Xml::createTag('key', array('position' => '1','color' => '#FFF000')),
                );
                
                unset($bar_settings['children']['bar_style']['children']['fill']);
                unset($bar_settings['children']['bar_style']['children']['border']);
                $bar_settings['properties']['scatter_point_width'] = '0.4%';
                $bar_settings['properties']['group_padding'] = '0';
                
                $bar_settings['children']['bar_style']['children']['fill']['properties'] = array(
                    'enabled' => 'true',
                    'type' => 'Solid',
                    'color' => '#fff000',
                    'thickness' => '1',
                );
                
                $bar_settings['children']['bar_style']['children']['border'] = array(
                    'properties' => array(
                        'enabled' => 'True',
                        'type' =>'Gradient'
                    ),
                    'children' => array(
                        'gradient' => array(
                            'properties' => array(
                                'angle' => '90'
                            ),
                        )
                    )
                );
                $bar_settings['children']['bar_style']['children']['border']['children']['gradient']['value'] = $keys;
                break;
            case 'global_radiation':
                unset($bar_settings['children']['bar_style']['children']['fill']);
                unset($bar_settings['children']['bar_style']['children']['border']);
                
                $bar_settings['children']['bar_style']['children']['fill'] = array(
                    'properties' => array(
                        'enabled' => 'true',
                        'type' => 'Solid',
                        'color' => '#182DCC',
                        'thickness' => '1',
                    )
                );
                break;
            case 'precipitation':
                // This is a quick fix for the nested same key names
                $keys = array(
                    Xml::createTag('key', array('position' => '0','color' => '#0036D9')),
                    Xml::createTag('key', array('position' => '1','color' => '#002080')),
                );
                
                
                break;
                $bar_settings['children']['bar_style']['children']['fill']['children']['gradient']['value'] = $keys;
            case 'airpressure':
                $keys = array(
                    Xml::createTag('key', array('position' => '0','color' => '#F5E616')),
                    Xml::createTag('key', array('position' => '1','color' => '#E3D50B')),
                );
                $bar_settings['children']['bar_style']['children']['fill']['children']['gradient']['value'] = $keys;
                break;
        }
        
        switch(self::$properties['chart_type']){
            case 'temp':
            case 'temperature':
            case 'wind':
            case 'winddir':
            case 'dir':
            case 'direction':
            case 'humidity':
                $graph_type = 'line_series';
                $graph_settings = $line_settings;
                break;
            case 'sunshine':
            case 'airpressure':
            case 'globalradiation':
            case 'precipitation':
            case 'precip':
                $graph_type = 'bar_series';
                $graph_settings = $bar_settings;
                break;
        }
        
        $data_plot_settings = array(
            'properties' => array(
                'default_series_type' => self::$data['default_series_type']
            ),
            'children' => array(
                $graph_type => $graph_settings
            )
        );
        return $data_plot_settings;
    }
    
    public static function createChart($type, $arrData){
//        CakeLog::write('anychart', print_r($arrData, true));
        
        self::$properties['chart_type'] = $type;
        self::$data = $arrData['settings'];
        
        $anychart = array(
            'anychart' => array(
                'children' => array(
                    'settings' => self::globalSettings(),
                    'charts' => array(
                        'properties' => array(
                            'plot_type' => 'Scatter'
                        ),
                        'children' => array(
                            'chart_settings' => self::chartSettings(),
                            'data_plot_settings' => self::dataPlotSettings(),
                        ),
                    ),
                )
            )
        );
        
        CakeLog::write('anychart', print_r($anychart, true));
        
        return $anychart;
    }
    
    
}