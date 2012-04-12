<?php
/**
 * Weatherph Compact
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
            'libs/jquery.geo-1.0a4',
        ));
        echo $scripts_for_layout;
        ?>
    </head>
    <body>
        <section id="container">
            <header class="banner clear">    
                <h1 class="logo">weather | philippines</h1>
                
                <nav>
                    <ul>
                        <li>
                            <form class="search">
                                Search: <input type="text" name="city" size="15" />
                            </form>
                        </li>
                        <li>
                        Select by region:
                        <select name="philippine-regions">
                            <option>Choose one...</option>

                            <optgroup label="Luzon">
                                <option data-region-id="NCR">NCR</option>
                                <option data-region-id="CAR">CAR</option>
                                <option data-region-id="I">Ilocos</option>
                                <option data-region-id="II">Cagayan Valley</option>
                                <option data-region-id="III">Central Luzon</option>
                                <option data-region-id="IVa">CALABARZON</option>
                                <option data-region-id="IVb">MIMAROPA</option>
                                <option data-region-id="V">Bicol</option>
                            </optgroup>

                            <optgroup label="Visayas">
                                <option data-region-id="VI">Western Visayas</option>
                                <option data-region-id="VII">Central Visayas</option>
                                <option data-region-id="VIII">Eastern Visayas</option>
                            </optgroup>

                            <optgroup label="Mindanao">
                                <option data-region-id="IX">Zamboanga Peninsula</option>
                                <option data-region-id="X">Northern Mindanao</option>
                                <option data-region-id="XI">Davao</option>
                                <option data-region-id="XII">SOCCSKSARGEN</option>
                                <option data-region-id="XIII">CARAGA</option>
                                <option data-region-id="ARMM">ARMM</option>
                            </optgroup>
                        </select>
                    </li>
                </nav>
            </header> <!--BANNER-->

            <nav>
                <ul class="dropdown">
                    <li>
                        <a href="#">Local Weather &#9662;</a>
                        <ul>
                            <li><a href="#">Travel Advisories</a></li>
                            <li><a href="#">Severe Weather Warnings</a></li>
                        </ul>
                    <li>
                        <a href="#">Detailed Weather Reports &#9662;</a>
                        <ul>
                            <li><a href="#">Weather Stations</a></li>
                            <li><a href="#">Weather for Professionals</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Resources &#9662;</a>
                        <ul>
                            <li><a href="#">Mr. Typhoon's Weather Blog</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Glossary</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <div id="content">
                <section class="main">
                    <div id="currentWeather">
                        <div id="station">
                            <h1>Makati</h1>
                            <h6>Current readings from:</h6>
                            <h6>Salcedo weather station</h6>
                            <h6>change station</h6>
                        </div>
                        
                        <div id="condition">
                            <img src="theme/weatherph/img/cloudy1.png"/>
                            <div class="condition-text"> 
                                <h3>Partly Cloudy</h3>
                                <h2>31&#8451;</h2>
                                <ul>
                                    <li>Sunrise: 5:38AM</li>
                                    <li>Sunset: 6:53PM</li>
                                    <li>Moon: Waxing</li>
                                </ul>
                            </div>
                        </div>
                        <div id="conditionTable">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Precipitation</td>
                                        <td class="output">.08l/m</td>
                                    </tr>
                                    <tr>
                                        <td>Avg. Wind Speed</td>
                                        <td class="output">12km/h</td>
                                    </tr>
                                    <tr>
                                        <td>Relative Humidity</td>
                                        <td class="output">63%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div id="week">
                        <h4>This week's forecast</h4>
                    </div>
                    
                </section> <!--MAIN CONTENT-->

                <section class="secondary">
                    <div id="charts">
                        
                    </div>
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
