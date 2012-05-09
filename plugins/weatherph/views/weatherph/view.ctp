<?php //echo $this->Html->script('AnyChart.js'); ?>

<script type="text/javascript" src="../weatherph/js/weatherph/view.js"></script>
<script type="text/javascript" src="../weatherph/js/weatherph/AnyChart.js"></script>

<div class="content">
    <section class="main">
        <div id="currentWeather">
            <div id="station">
                <p>Current readings from:</p>
                <h1><?= $weeklyForecasts['ort1']; ?></h1>
                <a href="#">change station</a>
            </div> <!--END STATION-->

            <div id="condition">
                <table>
                    <tbody>
                        <tr>
                            <td><!--img src="/theme/weatherph/img/cloudy.png"/--></td>
                            <td><?= $weeklyForecasts['reading']['tl']; ?>&deg;C</td>
                        </tr>
                        <tr>
                            <td><span class="symbol sunrise"></span></td>
                            <td>Sunrise: <?= date("h:iA",strtotime($weeklyForecasts['reading']['sunrise'])); ?></td>
                        </tr>
                        <tr>
                            <td><span class="symbol sunset"></span></td>
                            <td>Sunset: <?= date("h:iA",strtotime($weeklyForecasts['reading']['sunset'])); ?></td>
                        </tr>
                        <tr>
                            <td><span class="symbol moonphase_<?= $weeklyForecasts['reading']['moonphase']['phase_code']; ?>"></span></td>
                            <td>Moon Phase: <?= $weeklyForecasts['reading']['moonphase']['phase']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div> <!--END CONDITION-->
            <div id="conditionTable">
                <table>
                    <tbody>
                        <tr>
                            <td class="caption">Precipitation</td>
                            <td class="output"><?= $weeklyForecasts['reading']['rr']; ?>mm</td>
                        </tr>
                        <tr>
                            <td class="caption">Avg. Wind Speed</td>
                            <td class="output"><?= $weeklyForecasts['reading']['ff']; ?>km/h</td>
                        </tr>
                        <tr>
                            <td class="caption">Relative Humidity</td>
                            <td class="output"><?= $weeklyForecasts['reading']['rh']; ?>%</td>
                        </tr>
                    </tbody>
                </table>
            </div> <!--END CONDITON TABLE-->
        </div> <!--END CURRENT WEATHER-->

        <div id="weekWeather">
            <h4>This week's forecast</h4>
            <ul id="week-forecast" class="tabs">
            <?php
            $start_date = date('Y-m-d');
            $check_date = $start_date; 
            $end_date = date('Y-m-d', strtotime('+5 days')); 

            $i = 0; 
            while ($check_date != $end_date) {
                $class = ($i == 0)? "current-tab" : "";
                $dayName = ($i == 0)? "Today" : date('l', strtotime($check_date));
            ?>
                <li id="<?= strtolower($dayName); ?>" class="<?= $class; ?>" ><?= $dayName; ?></li>
            <?php $check_date = date ("Y-m-d", strtotime ("+1 day", strtotime($check_date))); $i++; 
                if ($i > 31) { die ('Error!'); }
            }  
            
            ?>
            </ul>
            
            <div class="tab-container">
                
            <?php foreach ($weeklyForecasts['forecast'] as $key => $dayForecast) {
                $today = date("Ymd");
                $tab_class = ($key == $today)? 'current-tab' : 'tab';
                $div_id = ($key == $today)? "Today" : date('l', strtotime($key));
                ?>
                <div id="<?= strtolower($div_id); ?>" class="<?= $tab_class; ?>">
                    <table class="week-forecast" cellspacing="0">
           
                    <tr class="time">
                        <td class="caption">Time</td>   
                        <?php foreach (Set::extract($dayForecast, '{n}.utch') as $column) { ?>
                            <td><?= $column; ?></td>
                        <?php } ?>
                    </tr>
                    
                    <tr class="condition">
                        <td class="caption">Condition</td>
                        <?php foreach (Set::extract($dayForecast, '{n}.sy') as $column) { ?>
                            <td><span class="symbol <?= $column; ?>"></span></td>
                        <?php } ?>
                    </tr>
                    
                    <tr class="temperature">
                        <td class="caption">Temperature</td>   
                        <?php foreach (Set::extract($dayForecast, '{n}.tl') as $column) { ?>
                            <td><?= $column; ?>&deg;C</td>
                        <?php } ?>
                    </tr>
                    
                    <tr class="precipitation">
                        <td class="caption">Precipitation</td>   
                        <?php foreach (Set::extract($dayForecast, '{n}.rr') as $column) { ?>
                        <td><?= $column; ?>mm</td>
                        <?php } ?>
                    </tr>
                    
                    <tr class="wind">
                        <td class="caption">Wind speed</td>   
                        <?php foreach (Set::extract($dayForecast, '{n}.ff') as $column) { ?>
                            <td><?= $column; ?>km/h</td>
                        <?php } ?>
                    </tr>
                    
                    <tr class="direction">
                        <td class="caption">Wind Direction</td>   
                        <?php foreach (Set::extract($dayForecast, '{n}.dir') as $column) { ?>
                            <td><span class="symbol <?= $column; ?>"></span></td>
                        <?php } ?>
                    </tr>
           
                    </table>
                </div>
            <?php } ?>
            
            </div>
                
        </div> <!--END WEEK WEATHER-->

    </section> <!--MAIN CONTENT-->
    

    <section class="secondary">
        <div id="charts">
            <h4>Detailed Forecasts</h4>
            <ul class="tabs">
                <li class="temperature flip current-tab"><a href="#">Temperature</a></li>
                <li class="precipitation flip"><a href="#">Precipitation</a></li>
                <li class="wind flip"><a href="#">Wind</a></li>
                <li class="humidity flip"><a href="#">Humidity</a></li>
            </ul>
            <div class="tab-container">
                <div class="temperature panel current-tab">
                  <script type="text/javascript" language="javascript">
                        //<![CDATA[
                            var chart = new AnyChart('../weatherph/swf/AnyChart.swf');
                            chart.width = 830;
                            chart.height = 200;
                            chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $weeklyForecasts['stationId']; ?>/temperature/3h');
                            chart.write();
                        //]]>
                    </script>
                </div>
                <div class="precipitation panel">
                  <script type="text/javascript" language="javascript">
                        //<![CDATA[
                            var chart = new AnyChart('../weatherph/swf/AnyChart.swf');
                            chart.width = 830;
                            chart.height = 200;
                            chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $weeklyForecasts['stationId']; ?>/precip');
                            chart.write();
                        //]]>
                    </script>
                </div>
                <div class="wind panel">
                  <script type="text/javascript" language="javascript">
                        //<![CDATA[
                            var chart = new AnyChart('../weatherph/swf/AnyChart.swf');
                            chart.width = 830;
                            chart.height = 200;
                            chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $weeklyForecasts['stationId']; ?>/wind');
                            chart.write();
                        //]]>
                    </script>
                </div>
                <div class="humidity panel">
                  <script type="text/javascript" language="javascript">
                        //<![CDATA[
                            var chart = new AnyChart('../weatherph/swf/AnyChart.swf');
                            chart.width = 830;
                            chart.height = 200;
                            chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $weeklyForecasts['stationId']; ?>/humidity');
                            chart.write();
                        //]]>
                    </script>
                </div>

            </div>
        </div> <!--END CHARTS-->

        <div id="outlook">
            <h4>15-Day Outlook</h4>
            <ul class="tabs">
                <li class="current-tab"><a href="javascript: void(0);">Temperature</a></li>
                <li><a href="javascript: void(0);">Precipitation</a></li>
                <li><a href="javascript: void(0);">Wind</a></li>
            </ul>
            <?php echo $this->Html->image('chart.png'); ?>
        </div> <!--END OUTLOOK-->
    </section> <!--SECONDARY-->
</div> <!--CONTENT-->
