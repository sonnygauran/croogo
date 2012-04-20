<?php
/**
 * Weatherph Detail
 *
 * @author Martin de Lima <mdelima@meteomedia.com.ph>
 * @link http://www.weather.com.ph
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $title_for_layout; ?> &raquo; <?php echo Configure::read('Site.title'); ?></title>
        <?php
        echo $this->Layout->meta();
        echo $this->Layout->feed();
        echo $this->Html->css(array(
            'reset',
            'theme',
        ));
        echo $this->Layout->js();
        echo $this->Html->script(array(
            'jquery/jquery.min',
            'jquery/jquery.hoverIntent.minified',
            'jquery/superfish',
            'jquery/supersubs',
            'theme',
            'script',
            'AnyChart',
            'AnyChartHTML5',
            'libs/jquery.geo-1.0a4',
        ));
        echo $scripts_for_layout;
        ?>
    </head>
    <body>
        <section id="container">
            <header class="banner clear">    
                <h1 class="logo">weather | philippines</h1>
                
                <div id="options">
                    <form class="search">
                        Search: <input type="text" name="city" size="15" />
                    </form>
                </div>
            </header> <!--BANNER-->

            <nav>
                <ul>
                    <li><a href="#">Travel Advisories</a></li>
                    <li><a href="#">Severe Weather Warnings</a></li>
                    <li><a href="#">Weather Stations</a></li>
                    <li><a href="#">Weather for Professionals</a></li>
                    <li><a href="#">Mr. Typhoon's Weather Blog</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Glossary</a></li>
                </ul>
            </nav>

            <div id="content">
                <section class="main">
                    <div id="currentWeather">
                        <div id="station">
                            <h1>Makati</h1>
                            <br/><br/>
                            <p>Current readings from:</p>
                            <h6>Salcedo weather station</h6>
                            <p>change station</p>
                        </div> <!--END STATION-->
                        
                        <div id="condition">
                            <img src="theme/weatherph/img/cloudy1.png"/>
                            <div class="condition-text"> 
                                <h3>Partly Cloudy</h3>
                                <h2>31&deg;C</h2>
                                <br/>
                                <ul>
                                    <li>Sunrise: 5:38AM</li>
                                    <li>Sunset: 6:53PM</li>
                                    <li>Moon: Waxing</li>
                                </ul>
                            </div> <!--END CONDITON TEXT-->
                        </div> <!--END CONDITION-->
                        <div id="conditionTable">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="caption">Precipitation</td>
                                        <td class="output">.08l/m</td>
                                    </tr>
                                    <tr>
                                        <td class="caption">Avg. Wind Speed</td>
                                        <td class="output">12km/h</td>
                                    </tr>
                                    <tr>
                                        <td class="caption">Relative Humidity</td>
                                        <td class="output">63%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <!--END CONDITON TABLE-->
                    </div> <!--END CURRENT WEATHER-->
                    
                    <div id="weekWeather">
                        <h4>This week's forecast</h4>
                        <ul class="tabs">
                            <li class="current-tab"><a href="#">Today</a></li>
                            <li><a href="#">Saturday</a></li>
                            <li><a href="#">Sunday</a></li>
                            <li><a href="#">Monday</a></li>
                            <li><a href="#">Tuesday</a></li>
                        </ul>
                        
                        <div class="tab-container">
                            <div class="current-tab">
                            <table class="week-forecast" cellspacing="0">
                                    <tbody>
                                        <tr class="time">
                                            <td class="caption"></td>
                                            <td>8:00</td>
                                            <td>11:00</td>
                                            <td>14:00</td>
                                            <td>17:00</td>
                                            <td>20:00</td>
                                            <td>23:00</td>
                                            <td>2:00</td>
                                        </tr>
                                        <tr class="condition">
                                        <td class="caption">Condition</td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                        </tr>
                                        <tr class="temperature">
                                        <td class="caption">Temperature</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                        </tr>
                                        <tr class="precipitation">
                                            <td class="caption">Precipitation</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                        </tr>
                                        <tr class="wind">
                                            <td class="caption">Wind speed / direction</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!--TABLE CONTAINER-->
                            <div class="tab">
                            <table class="week-forecast" cellspacing="0">
                                    <tbody>
                                        <tr class="time">
                                            <td class="caption"></td>
                                            <td>8:00</td>
                                            <td>11:00</td>
                                            <td>14:00</td>
                                            <td>17:00</td>
                                            <td>20:00</td>
                                            <td>23:00</td>
                                            <td>2:00</td>
                                        </tr>
                                        <tr class="condition">
                                        <td class="caption">Condition</td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                        </tr>
                                        <tr class="temperature">
                                        <td class="caption">Temperature</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                        </tr>
                                        <tr class="precipitation">
                                            <td class="caption">Precipitation</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                        </tr>
                                        <tr class="wind">
                                            <td class="caption">Wind speed / direction</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!--TABLE CONTAINER-->
                            <div class="tab">
                            <table class="week-forecast" cellspacing="0">
                                    <tbody>
                                        <tr class="time">
                                            <td class="caption"></td>
                                            <td>8:00</td>
                                            <td>11:00</td>
                                            <td>14:00</td>
                                            <td>17:00</td>
                                            <td>20:00</td>
                                            <td>23:00</td>
                                            <td>2:00</td>
                                        </tr>
                                        <tr class="condition">
                                        <td class="caption">Condition</td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                        </tr>
                                        <tr class="temperature">
                                        <td class="caption">Temperature</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                        </tr>
                                        <tr class="precipitation">
                                            <td class="caption">Precipitation</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                        </tr>
                                        <tr class="wind">
                                            <td class="caption">Wind speed / direction</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!--TABLE CONTAINER-->
                            <div class="tab">
                            <table class="week-forecast" cellspacing="0">
                                    <tbody>
                                        <tr class="time">
                                            <td class="caption"></td>
                                            <td>8:00</td>
                                            <td>11:00</td>
                                            <td>14:00</td>
                                            <td>17:00</td>
                                            <td>20:00</td>
                                            <td>23:00</td>
                                            <td>2:00</td>
                                        </tr>
                                        <tr class="condition">
                                        <td class="caption">Condition</td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                        </tr>
                                        <tr class="temperature">
                                        <td class="caption">Temperature</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                        </tr>
                                        <tr class="precipitation">
                                            <td class="caption">Precipitation</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                        </tr>
                                        <tr class="wind">
                                            <td class="caption">Wind speed / direction</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!--TABLE CONTAINER-->
                            <div class="tab">
                            <table class="week-forecast" cellspacing="0">
                                    <tbody>
                                        <tr class="time">
                                            <td class="caption"></td>
                                            <td>8:00</td>
                                            <td>11:00</td>
                                            <td>14:00</td>
                                            <td>17:00</td>
                                            <td>20:00</td>
                                            <td>23:00</td>
                                            <td>2:00</td>
                                        </tr>
                                        <tr class="condition">
                                        <td class="caption">Condition</td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                            <td><img class="medium" src="theme/weatherph/img/sunny.png" alt="sunny" /></td>
                                        </tr>
                                        <tr class="temperature">
                                        <td class="caption">Temperature</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                            <td>31&deg;C</td>
                                        </tr>
                                        <tr class="precipitation">
                                            <td class="caption">Precipitation</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                            <td>63%</td>
                                        </tr>
                                        <tr class="wind">
                                            <td class="caption">Wind speed / direction</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                            <td>12km/h (NW)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!--TABLE CONTAINER-->
                        </div> <!--TAB CONTAINER-->
                    </div> <!--END WEEK WEATHER-->
                    
                </section> <!--MAIN CONTENT-->

                <section class="secondary">
                    <div id="charts">
                        <h4>Detailed Forecasts</h4>
                        <ul class="tabs">
                            <li class="current-tab"><a href="#">Temperature</a></li>
                            <li><a href="#">Precipitation</a></li>
                            <li><a href="#">Wind</a></li>
                            <li><a href="#">Humidity</a></li>
                        </ul>
                        <div class="tab-container">
                            <div class="current-tab">
                                <script type="text/javascript" language="javascript"> 
                                //<![CDATA[
                                AnyChart.renderingType = anychart.RenderingType.SVG_ONLY; 
                                var chart = new AnyChart();
                                chart.width = 800;
                                chart.height = 300;
                                chart.setXMLFile('/anychart.xml');
                                chart.write();
                                //]]>
                                </script>
                            </div>
                        </div>
                    </div> <!--END CHARTS-->
                    
                    <div id="outlook">
                        <h4>15-Day Outlook</h4>
                        <ul class="tabs">
                            <li class="current-tab"><a href="#">Temperature</a></li>
                            <li><a href="#">Precipitation</a></li>
                            <li><a href="#">Wind</a></li>
                        </ul>
                    </div> <!--END OUTLOOK-->
                </section> <!--SECONDARY-->
            </div> <!--CONTENT-->

            <aside id="sidebar">
                <div id="sponsors">
                    <div class="sponsored center">
                        <h6>Powered by:</h6>
                        <?php echo $this->Html->image('aboitiz.jpg'); ?>
                    </div>
                    <div class="sponsored center">
                        <h6>Platinum sponsors:</h6>
                        <ul>
                            <li><?php echo $this->Html->image('ICTS.jpg'); ?></li>
                            <li><?php echo $this->Html->image('NAC.jpg'); ?></li>
                            <li><?php echo $this->Html->image('SGS.jpg'); ?></li>
                            <li><?php echo $this->Html->image('vistaland.jpg'); ?></li>
                            <li><?php echo $this->Html->image('NGCP.png'); ?></li>
                            <li><?php echo $this->Html->image('sumitomo.png'); ?></li>
                        </ul>
                    </div>
                </div>

                <div class="ads">
                    <div class="promo">
                        <h4>Visit Boracay!</h4>
                        <img src="theme/weatherph/img/boracay.jpg" alt="Boracay!"/>
                        <p>
                            Boracay is an island of the Philippines located approximately 315 km (196 mi) south of Manila. Boracay Island and its beaches have received awards numerous times.
                        </p>
                    </div>

                    <div class="promo">
                        <h4>Discover Pamalican.</h4>
                        <img src="theme/weatherph/img/pamalican.jpg" alt="Pamalican!"/>
                        <p>
                            Pamalican Island is a small island of the Cuyo Islands in the Sulu Sea.
                        </p>
                    </div>
                </div>
            </aside>
        
            <footer>
                <div class="countrySelect">
                    <h6>Our severe weather centers</h6>
                    <ul>
                        <li><a href="http://www.wetteralarm.at/">Austria</a></li>
                        <li><a href="http://www.meteo-info.be/">Belgium</a></li>
                        <li><a href="http://www.vejrcentral.dk/">Denmark</a></li>
                        <li><a href="http://www.vigilance-meteo.fr/">France</a></li>
                    </ul>
                    <ul>
                        <li><a href="http://www.unwetterzentrale.de/">Germany</a></li>
                        <li><a href="http://www.meteo-allerta.it/">Italy</a></li>
                        <li><a href="http://www.meteocentrale.li/">Liechtenstein</a></li>
                    </ul>
                    <ul>
                        <li><a href="http://www.meteozentral.lu/">Luxembourg</a></li>
                        <li><a href="http://www.noodweercentrale.nl/">Netherlands</a></li>
                        <li><a href="http://www.alertas-tiempo.es/">Spain</a></li>
                    </ul>
                    <ul>
                        <li><a href="http://www.vader-alarm.se/">Sweden</a></li>
                        <li><a href="http://www.meteocentrale.ch/">Switzerland</a></li>
                        <li><a href="http://www.severe-weather-centre.co.uk/">United Kingdom</a></li>
                    </ul>
                </div>
                <div class="legal">
                    <h6>&copy; 2012 Meteomedia AG.</h6>
                    <ul>
                        <li>About</li>
                        <li>Legal</li>
                        <li>Contact</li>
                    </ul>
                </div>
            </footer>

        </section><!-- #container -->

        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </body>
</html>
