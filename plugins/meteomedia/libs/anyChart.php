<?php

App::import('Lib', 'Meteomedia.Xml');

/**
 * @author Jaggy Gauran
 *  
 * Meteomedia Plugin - Any Chart 
 * 
 */
class AnyChart {

    private static $chartData = array();
    
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
            'family' => 'Arial',
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
                'locale' => array(
                    'children'=> array(
                        'date_time_format' => array(
                            'properties' => array(),
                            'children' => array(
                                'format' => array(
                                    'properties' => array(),
                                    'value' => '%u',
                                ),
                                'week_days'=> array(
                                    'properties' => array(
                                            'start_from' => 'Sunday'
                                    ),
                                    'children' => array(
                                        'short_names' => array(
                                            'properties' => array(),
                                            'value' => '<![CDATA[ Su.,Mo.,Tu.,We.,Th.,Fr.,Sa. ]]>',
                                        )
                                    ),
                                )
                            ),
                        ),
                        
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
                    'value' => array(
                        self::$properties['titles']['y_axis']
                    ),
                ),
                'labels' => array(
                    'properties' => array(),
                    'children' => array(
                        'format' => array(
                            'properties' => array(),
                            'value' => (self::$properties['chart_type'] == 'precip' || self::$properties['chart_type'] == 'precipitation')? '{%Value}{numDecimals:1}' : '{%Value}{numDecimals:0}'
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
                        'maximum' => 1,
                        'minimum' => 0,
                        'maximum_offset' => 0.01,
                        'minimum_offset' => 0.01,
                    ),
                    
                );
                break;
            case 'humidity':
                $y_axis['children']['scale'] = array(
                    'properties' => array(
                        'type' => 'Linear',
                        'maximum' => 100,
                        'minimum' => 0,
                    ),
                );
                break;
        }

        
        $extra = array(
            'properties' => array(),
            'children' => array(
                'y_axis' => array(
                    'properties' => array(
                        'name' =>'y2',
                        'enabled' => 'False'
                    ),
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
                'border' => array(
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
                                'enabled' => 'False',
                            ),
                            'value' => null
                        ),
                        'effects' => array(
                            'properties' => array(
                                'enabled' => 'False',
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
                $bar_settings = array();
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
            case 'precip':
            case 'precipitation':
                // This is a quick fix for the nested same key names
                $keys = array(
                    Xml::createTag('key', array('position' => '0','color' => '#0036d9')),
                    '<!-- innen -->',
                    Xml::createTag('key', array('position' => '1','color' => '#002080')),
                );
                
                
                $bar_settings['children']['bar_style']['children']['fill']['children']['gradient']['properties'] = array(
                    'type' => 'Radial'
                );
                $bar_settings['children']['bar_style']['children']['fill']['children']['gradient']['value'] = $keys;
                break;
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
    
    public static function chartStyles(){
        
        $styles = array();
        
        switch(self::$properties['chart_type']){
            case 'temperature':
            case 'temp':
                $styles = array(
                    'properties' => array(),
                    'value' => array(
                        Xml::createTag('line_style', array('name'=>'tlline', 'color' =>'#c80000'), array(Xml::createTag('line', array('thickness'=> '2')))),
                        Xml::createTag('line_style', array('name'=>'tdline', 'color' =>'#00c800'), array(Xml::createTag('line', array('thickness'=> '2')))),
                        Xml::createTag('line_style', array('name'=>'noline'), array(Xml::createTag('line', array('enabled'=> 'false')))),
                        Xml::createTag('marker_style', array('name'=>'dotblue', 'color' =>'blue'), array(Xml::createTag('line', array('size'=> '3', 'type'=>'circle')))),
                        Xml::createTag('marker_style', array('name'=>'dotred', 'color' =>'c80000'), array(Xml::createTag('line', array('size'=> '3', 'type'=>'circle')))),
                    ),
                );
                break;
            case 'wind':
                $styles = array(
                    'properties' => array(),
                    'value' => array(
                        Xml::createTag('line_style', array('name'=>'ffline', 'color' =>'#966400')),
                        Xml::createTag('line_style', array('name'=>'g1line', 'color' =>'#c800aa')),
                    ),
                );
                break;
            case 'dir':
            case 'winddir':
                $styles = array(
                    'properties' => array(),
                    'value' => array(
                        Xml::createTag('line_style', array('name'=>'dirline', 'color' =>'green'), array(Xml::createTag('line', array('enabled' => 'false')))),
                        Xml::createTag('marker_style', array('name'=>'wind_1'), array(Xml::createTag('marker', array('type' => 'Image', 'image_url' => '../theme/weatherph/img/w1.png', 'size' =>'15')))),
                        Xml::createTag('marker_style', array('name'=>'wind_2'), array(Xml::createTag('marker', array('type' => 'Image', 'image_url' => '../theme/weatherph/img/w2.png', 'size' =>'15')))),
                        Xml::createTag('marker_style', array('name'=>'wind_3'), array(Xml::createTag('marker', array('type' => 'Image', 'image_url' => '../theme/weatherph/img/w3.png', 'size' =>'15')))),
                        Xml::createTag('marker_style', array('name'=>'wind_4'), array(Xml::createTag('marker', array('type' => 'Image', 'image_url' => '../theme/weatherph/img/w4.png', 'size' =>'15')))),
                        Xml::createTag('marker_style', array('name'=>'wind_5'), array(Xml::createTag('marker', array('type' => 'Image', 'image_url' => '../theme/weatherph/img/w5.png', 'size' =>'15')))),
                        Xml::createTag('marker_style', array('name'=>'wind_6'), array(Xml::createTag('marker', array('type' => 'Image', 'image_url' => '../theme/weatherph/img/w6.png', 'size' =>'15')))),
                        Xml::createTag('marker_style', array('name'=>'wind_7'), array(Xml::createTag('marker', array('type' => 'Image', 'image_url' => '../theme/weatherph/img/w7.png', 'size' =>'15')))),
                        Xml::createTag('marker_style', array('name'=>'wind_8'), array(Xml::createTag('marker', array('type' => 'Image', 'image_url' => '../theme/weatherph/img/w8.png', 'size' =>'15')))),
                        Xml::createTag('marker_style', array('name'=>'wind_9'), array(Xml::createTag('marker', array('type' => 'Image', 'image_url' => '../theme/weatherph/img/w9.png', 'size' =>'15')))),
                    ),
                );
                break;
            case 'humidity':
                $styles = array(
                    'properties' => array(),
                    'value' => array(
                        Xml::createTag('line_style', array('name'=>'rhline', 'color' =>'#00c800')),
                    ),
                );
                break;
        }
        
        return $styles;
    }
    
    public static function plotData(){

        $series = array();
        $series_properties = array();
        $series_children = array();

        foreach (self::$chartData['sets'] as $key => $sets) {
            $series_properties = array();
            $series_children = array();
            
            
            
            foreach (self::$chartData['series'][$key] as $index => $attr) {
                $series_properties[$index] = $attr; 

            }
            
            
            if(isset(self::$chartData['additional'][$key])){
                foreach (self::$chartData['additional'][$key] as $index => $addtnl) {
                    $index_properties = array();
                    foreach ($addtnl as $key2 => $add) {
                        $index_properties[$key2] = $add;
                    }

                    $series_children[count($series_children)] = Xml::createTag($index, $index_properties);
                }
            }
            
                
            foreach ($sets as $set) {
                $values = array();
                if(is_array($set)){
                    if(key_exists('x', $set)){
                        $values[0] = '<!-- ' . date('Y-m-d H:i:s', $set['x']) . '-->';
                        if (isset($set['marker'])){
                            $values[1] = Xml::createTag('marker', array('style' => $set['marker']));
                        }
                        $series_children[count($series_children)] = Xml::createTag('point', array('x' => $set['x'], 'y' => $set['y']), $values);
                    }
                }
            }
            
            
            
            $series[count($series)] = Xml::createTag('series', $series_properties, $series_children);
            unset($series_properties);
            unset($series_children);
        }
        
       
       return $series;
    }
    
    public static function createChart($type, $arrData){
        
        self::$properties['chart_type'] = $type;
        self::$data = $arrData['settings'];
        self::$chartData = $arrData;
        
        
        $anychart = array(
            'anychart' => array(
                'children' => array(
                    'margin' => self::$properties['margin'],
                    'settings' => self::globalSettings(),
                    'charts' => array(
                        'properties' => array(),
                        'children' => array(
                            'chart' => array(
                                'properties' => array(
                                    'plot_type' => 'Scatter'
                                ),
                                'children' => array(
                                    'chart_settings' => self::chartSettings(),
                                    'data_plot_settings' => self::dataPlotSettings(),
                                    'styles' => self::chartStyles(),
                                    'data' => array(
                                        'properties' => array(),
                                        'value' => self::plotData()
                                    )
                                )
                            )
                        ),
                    ),
                )
            )
        );
        
        
        return $anychart;
    }
    
    
}