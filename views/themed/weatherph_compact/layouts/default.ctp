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
                    </ul>
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

                    <div id="map">
                        <div class="layerSelector">
                            <ul>
                                <li><a href="#">Wind</a></li>
                                <li><a href="#">Rain</a></li>
                                <li><a href="#">Temperature</a></li>
                                <li><a href="#">Clouds</a></li>
                                <li><a href="#">View more layers</a></li>
                            </ul>
                        </div> <!--LAYER SELECTOR-->
                        <div id="overlay">
                            <div class="details">
                                <dl class="ort1 center">
                                    <dt>Makati</dt>
                                    <dd>&nbsp;</dd>
                                </dl>
                            </div>

                            <div class="readings center">
                                <h6>Current Readings</h6>
                                <h6>as of 2:24PM</h6>
                                <table class="details">
                                    <tbody>
                                        <tr class="temperature">
                                            <td>Temperature (&deg;C)</td>
                                            <td class="output">&nbsp;</td>
                                        </tr>
                                        <tr class="wind_speed">
                                            <td>Wind Speed (kph)</td>
                                            <td class="output">&nbsp;</td>
                                        </tr>
                                        <tr class="humidity">
                                            <td>Humidity</td>
                                            <td class="output">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h6>Forecasts</h6>
                                <table class="center">
                                    <tbody>
                                        <tr>
                                            <td>3PM</td>
                                            <td>
                                                <img class="small" src="theme/weatherph/img/sunny.png" alt="sunny" />
                                            </td>
                                            <td class="output">31&deg;</td>
                                        </tr>
                                        <tr>
                                            <td>6PM</td>
                                            <td>
                                                <img class="small" src="theme/weatherph/img/overcast.png" alt="overcast"/>
                                            </td>
                                            <td class="output">29&deg;</td>
                                        </tr>
                                        <tr>
                                            <td>9PM</td>
                                            <td>
                                                <img class="small" src="theme/weatherph/img/cloudy2_night.png" alt="cloudy"/>
                                            </td>
                                            <td class="output">30&deg;</td>
                                        </tr>
                                        <tr>
                                            <td>12AM</td>
                                            <td>
                                                <img class="small" src="theme/weatherph/img/cloudy3_night.png" alt="cloudy"/>
                                            </td>
                                                <td class="output">28&deg;</td>
                                        </tr>
                                        <tr>
                                            <td>3AM</td>
                                            <td>
                                                <img class="small" src="theme/weatherph/img/cloudy2_night.png" alt="cloudy"/>
                                                <td class="output">26&deg;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!--READINGS-->
                            <h6 class="center"><a href="#">View more details here.</a></h6>

                        </div> <!--INFOPANE-->
                        <div id="legend">
                            <ul>
                                <li><img src="http://placehold.it/24x12/dd22227"/> PAGASA stations</li>
                                <li><img src="http://placehold.it/24x12/0000ff" /> Meteomedia stations</li>
                                <li><img src="http://placehold.it/24x12/333333"> Inactive stations</li>
                            </ul>
                        </div>
                    </div> <!--MAP-->
                </section> <!--MAIN CONTENT-->

                <section class="secondary">
                    <div class="posts">
                        <h4>The Weather in Words</h4>
                        <p>
                            The climate in Switzerland is strongly influenced by the Alps extending across the country and representing the most important meteorological divide in Central Europe. Thanks to the Alps, large climatic differences in Switzerland are to be found within a small geographic area. The largest contrasts exist between the northern side of the Alps with its temperatre climate and the southern side of the Alps characterized by mediterranean climate.
                        </p>

                        <h4>Tomorrow</h4>
                        <p>
                            In the Swiss Midland, extending between Lake Geneva and Lake Constance as well as between Jura and the Pre-Alps, climatic conditions which are typical for Central Europe are to be found. The average annual temperature is just below 10 °C, with mean values around the freezing point in January and between 16 and 19 °C in July. The average annual precipitation amounts are just above 1000 mm. In winter often a so-called inversion layer results in a large-scale low stratus cloud coverage which partly persists for several days or even weeks. Thereby the cold wind from northeast (named Bise) is pressed between Jura and Pre-Alps and can thus reach gale-force on the western shores of Lake Geneva.
                        </p>
                    </div>
                    
                    <div class="twitter">
                        <h4>Breaking News</h4>
                        <ul>
                            <li><img class="small" src="theme/weatherph/img/thumbnail.png"/>Sunny day today. Please continue donating!</li>
                            <li><img class="small" src="theme/weatherph/img/thumbnail.png"/>Cloudy for the rest of the day in Manila. Help flood victims!</li>
                            <li><img class="small" src="theme/weatherph/img/thumbnail.png"/>Flooding at Espana. Avoid going there.</li>
                            <li><img class="small" src="theme/weatherph/img/thumbnail.png"/>Watch out later! It's gonna be stormy tonight.</li>
                            <li><img class="small" src="theme/weatherph/img/thumbnail.png"/>Bembang enters Philippine area of responsibility</li>
                        </ul>
                        <h6 class="center clear">Follow us on Twitter to get updates right in your timeline</h6>
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
