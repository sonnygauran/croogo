<?php
/**
* Weatherph main layout
*
* @author Sonny Gauran <sgauran@meteomedia.com.ph>
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
          // 'reset',
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

<header class="banner clear">    
   <h1 class="logo">weather | philippines</h1>
</header> <!--BANNER-->

<nav>
   <ul>
       <li>About</li>
       <li>Weather movies</li>
       <li>Legal</li>
       <li>Contact</li>
       <li>Select by region:
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

<section class="main clear">
   <div class="map floating" id="map">
       <div class="infoPane">
           
           <dl class="ort1 center">
                <dt>Place</dt>
                <dd>&nbsp;</dd>
           </dl>
           
           <div class="readings center">
               <h6>Current Readings</h6>
               <h6>as of 2:24PM</h6>
           <table>
               <tbody>
                   <tr>
                       <td class="temperature">TEMP</td>
                       <td class="output">31&deg;</td>
                   </tr>
                   <tr>
                       <td class="wind_speed">WIND</td>
                       <td class="output">12km/h</td>
                   </tr>
                   <tr>
                       <td class="rain_precipitation">RAIN</td>
                       <td class="output">0.8L/m</td>
                   </tr>
                   <tr>
                       <td class="param">HUMD</td>
                       <td class="output">86%</td>
                   </tr>
               </tbody>
           </table>

            <h6>Forecasts</h6>
                <table class="center">
                    <tbody>
                        <tr>
                            <td>3PM</td>
                            <td class="weatherIcon">
                                <img class="small" src="theme/weatherph/img/sunny.png" />
                            </td>
                            <td class="output">31&deg;</td>
                        </tr>
                        <tr>
                            <td>6PM</td>
                            <td class="weatherIcon">
                                <img class="small" src="theme/weatherph/img/overcast.png" />
                            </td>
                            <td class="output">29&deg;</td>
                        </tr>
                        <tr>
                            <td>9PM</td>
                            <td class="weatherIcon">
                                <img class="small" src="theme/weatherph/img/cloudy2_night.png" />
                            </td>
                            <td class="output">30&deg;</td>
                        </tr>
                        <tr>
                            <td>12AM</td>
                            <td class="weatherIcon">
                                <img class="small" src="theme/weatherph/img/cloudy3_night.png" />
                            <td class="output">28&deg;</td>
                        </tr>
                        <tr>
                            <td>3AM</td>
                            <td class="weatherIcon">
                                <img class="small" src="theme/weatherph/img/cloudy2_night.png" />
                            <td class="output">26&deg;</td>
                        </tr>
                    </tbody>
                </table>
           </div> <!--READINGS-->
           <h6 class="center">View more details here.</h6>

       </div> <!--INFOPANE-->
   </div> <!--MAP-->
   
   <div class="sponsored floating center">
       <h4>Powered by:</h4>
       <?php echo $this->Html->image('aboitiz.jpg'); ?>
       <h4>Platinum sponsors:</h4>
       <ul>
           <li><?php echo $this->Html->image('ICTS.jpg'); ?></li>
           <li><?php echo $this->Html->image('NAC.jpg'); ?></li>
           <li><?php echo $this->Html->image('SGS.jpg'); ?></li>
       </ul>
   </div>
</section> <!--MAIN CONTENT-->

<section class="secondary clear">
   <h1>Latest reports</h1>
   <div class="posts content">
       <p>
           The climate in Switzerland is strongly influenced by the Alps extending across the country and representing the most important meteorological divide in Central Europe. Thanks to the Alps, large climatic differences in Switzerland are to be found within a small geographic area. The largest contrasts exist between the northern side of the Alps with its temperatre climate and the southern side of the Alps characterized by mediterranean climate.
       </p>

       <p>
           In the Swiss Midland, extending between Lake Geneva and Lake Constance as well as between Jura and the Pre-Alps, climatic conditions which are typical for Central Europe are to be found. The average annual temperature is just below 10 °C, with mean values around the freezing point in January and between 16 and 19 °C in July. The average annual precipitation amounts are just above 1000 mm. In winter often a so-called inversion layer results in a large-scale low stratus cloud coverage which partly persists for several days or even weeks. Thereby the cold wind from northeast (named Bise) is pressed between Jura and Pre-Alps and can thus reach gale-force on the western shores of Lake Geneva.
       </p>
   </div>
   
   <div class="twitter floating content">
       <h4 class="center">Meteomedia on Twitter</h4>
       <ul>
           <li>Sunny day today. Please continue donating!</li>
           <li>Cloudy for the rest of the day in Manila. Help flood victims!</li>
           <li>Flooding at Espana.</li>
           <li>Watch out later! It's gonna be stormy tonight.</li>
           <li>Bembang enters Philippine area of responsibility</li>
       </ul>
       <h6 class="center">Follow us on Twitter to get updates right in your timeline</h6>
   </div>
   
   <div class="ads content">
       <div class="promo">
           <h4 class="center">Visit Boracay!</h4>
           <p>
               Boracay is an island of the Philippines located approximately 315 km (196 mi) south of Manila and 2 km off the northwest tip of Panay Island in the Western Visayas region of the Philippines. Boracay Island and its beaches have received awards numerous times.
           </p>
       </div>

       <div class="promo">
           <h4 class="center">Discover Pamalican.</h4>
           <p>
           Pamalican Island is a small island of the Cuyo Islands in the Sulu Sea, between Palawan and Panay, in the north part of the Palawan Province of the Philippines.
           </p>
       </div>
   </div>
</section> <!--SECONDARY CONTENT-->

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

</body>
</html>
