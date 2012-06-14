<?php //echo $this->Html->script('AnyChart.js'); ?>

<?php
//echo $this->Html->script(array(
//    $this->webroot.'weatherph/js/weatherph/view',
//    $this->webroot.'weatherph/js/weatherph/AnyChart',
//    )); 

echo $this->Html->script(array(
    $this->webroot.'weatherph/js/weatherph/view',
    $this->webroot.'weatherph/js/weatherph/AnyChart',
    )); 
?>

<!--<script type="text/javascript" scr="<?= $this->webroot ?>js/weatherph/view.js"></script>
<script type="text/javascript" scr="<?= $this->webroot ?>js/weatherph/AnyChart.js"></script>-->

<div class="content">
    <section class="main">
        <div id="currentWeather" class="shadow">
            
            <div id="station">
                <h1><?= $dataSets['stationName']; ?></h1>
<!--                <a href="#">change station</a>-->
            </div> <!--END STATION-->

            <?php if($dataSets['reading']['status'] == 'ok'): ?>
            <div id="condition">
                <table>
                    <tbody>
                        <tr>
                            <td><span class="symbol sunrise"></span></td>
                            <td>Sunrise: <?= date("h:iA",strtotime($dataSets['reading']['sunrise'])); ?></td>
                        </tr>
                        <tr>
                            <td><span class="symbol sunset"></span></td>
                            <td>Sunset: <?= date("h:iA",strtotime($dataSets['reading']['sunset'])); ?></td>
                        </tr>
                        <tr>
                            <td><span class="symbol moonphase_<?= $dataSets['reading']['moonphase']['phase_code']; ?>"></span></td>
                            <td>Moon Phase: <?= $dataSets['reading']['moonphase']['phase']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div> <!--END CONDITION-->
            
            <div id="conditionTable">
                <table>
                    <tbody>
                        <tr>
                            <td class="caption">Avg. Wind Speed</td>
                            <td class="output"><?= $dataSets['reading']['ff']; ?>km/h</td>
                        </tr>
                        <tr>
                            <td class="caption">Precipitation</td>
                            <td class="output"><?= $dataSets['reading']['rr']; ?>mm</td>
                        </tr>
                        <tr>
                            <td class="caption">Relative Humidity</td>
                            <td class="output"><?= $dataSets['reading']['rh']; ?>%</td>
                        </tr>
                    </tbody>
                </table>
            </div> <!--END CONDITION TABLE-->
            <?php endIf; ?>
            
        </div> <!--END CURRENT WEATHER-->
        <?php if($dataSets['reading']['sunrise'] == 'none'): ?>
            <div class="no-readings" style="display: block;">
                <p>Sorry, there are no readings available for this station.</p>
            </div>
        <?php endIf; ?>
        
        <div id="weekWeather">
            <h4>This week's forecast</h4>
            
            <div class="tab-container">
            <?php foreach ($dataSets['forecast'] as $key => $dayForecasts) {
                
                $today = date("Ymd");
                $forecast_day_name = ($key == $today)? "Today" : date('l', strtotime($key));
                
                ?>
                <div class="day-forecast-wrapper">
                    <h5><?= $forecast_day_name; ?>, <?= date('F d, Y', strtotime($key));?></h5>
                    <table>
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Condition</th>
                                <th>Temperature</th>
                                <th>Precipitation</th>
                                <th>Wind Speed</th>
                                <th>Wind Direction</th>
                            </tr>
                        </thead>
                        <tbody>
            <?php foreach($dayForecasts as $forecast){ ?>
                            <tr>
                                <td><?= $forecast['localtime_range']; ?></td>
                                <td><span class="symbol <?= $forecast['weather_symbol']; ?>"></span>
                                <td><?= $forecast['temperature']; ?>&deg;C</td>
                                <td><?= $forecast['precipitation']; ?>mm</td>
                                <td><?= $forecast['wind_speed']; ?>km/h</td>
                                <td class="left"><span class="symbol <?= $forecast['wind_direction']; ?>"></span><span class="wind-description"><?= $forecast['wind_description']?></span></td>
                            </tr>
            <? }?>            
                        </tbody>
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
                            var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                            chart.width = 794;
                            chart.height = 200;
                            chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['stationId']; ?>/temperature/3h');
                            chart.write();
                        //]]>
                    </script>
                </div>
                <div class="precipitation panel">
                  <script type="text/javascript" language="javascript">
                        //<![CDATA[
                            var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                            chart.width = 794;
                            chart.height = 200;
                            chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['stationId']; ?>/precip');
                            chart.write();
                        //]]>
                    </script>
                </div>
                <div class="wind panel">
                  <script type="text/javascript" language="javascript">
                        //<![CDATA[
                            var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                            chart.width = 794;
                            chart.height = 200;
                            chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['stationId']; ?>/wind');
                            chart.write();
                        //]]>
                    </script>
                  <script type="text/javascript" language="javascript">
                        //<![CDATA[
                            var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                            chart.width = 794;
                            chart.height = 50;
                            chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['stationId']; ?>/winddir/6h');
                            chart.write();
                        //]]>
                    </script>
                </div>
                <div class="humidity panel">
                  <script type="text/javascript" language="javascript">
                        //<![CDATA[
                            var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                            chart.width = 794;
                            chart.height = 200;
                            chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['stationId']; ?>/humidity');
                            chart.write();
                        //]]>
                    </script>
                </div>
            </div>
        </div> <!--END CHARTS-->
<!--        <div id="outlook">
            <h4>15-Day Outlook</h4>
            <ul class="tabs">
                <li class="current-tab"><a href="javascript: void(0);">Temperature</a></li>
                <li><a href="javascript: void(0);">Precipitation</a></li>
                <li><a href="javascript: void(0);">Wind</a></li>
            </ul>
        </div> END OUTLOOK-->
    </section> <!--SECONDARY-->
</div> <!--CONTENT-->