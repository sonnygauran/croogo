<?php //echo $this->Html->script('AnyChart.js');      ?>

<?php
//echo $this->Html->script(array(
//    $this->webroot.'weatherph/js/weatherph/view',
//    $this->webroot.'weatherph/js/weatherph/AnyChart',
//    )); 

echo $this->Html->script(array(
    //$this->webroot . 'weatherph/js/weatherph/view',
    //$this->webroot . 'weatherph/js/weatherph/AnyChart',
));
?>

<!--<script type="text/javascript" scr="<?= $this->webroot ?>js/weatherph/view.js"></script>
<script type="text/javascript" scr="<?= $this->webroot ?>js/weatherph/AnyChart.js"></script>-->

<div class="content">
    <section class="main">
        <div id="currentWeather" class="shadow">

            <div id="station">
                <h1><?= $dataSets['stationName']; ?></h1>
                <h6>Current readings</h6>
                <!-- <a href="#">change station</a>-->
            </div> <!--END STATION-->

            <?php if ($dataSets['reading']['status'] == 'ok'): ?>
            
                <div id="condition">

                        <?php if (!empty($dataSets['reading']['sy']['symbol'])) { ?>
                        <div class="inner-condition">
                        <div class="left-temp-reading">
                        <?= $dataSets['reading']['temperature']; ?>&deg;C
                        </div>
                        
                        <div class="right-sy-reading">
                        <span class="symbol <?= $dataSets['reading']['sy']['symbol']; ?>" title="<?= $dataSets['reading']['sy']['description']; ?>" ></span>
                        </div>
                        </div>    
                        <?php } else { ?>
                        <div class="inner-condition-temp-reading-only">
                        <div class="reading-temperature-only">
                            
                        <?= $dataSets['reading']['temperature']; ?>&deg;C
                        </div>
                        </div>
                        <?php } ?>
                                        
                    <table>
                        <tbody>
                            <tr>
                                <td><span class="symbol sunrise"></span></td>
                                <td>Sunrise: <?= date("h:iA", strtotime($dataSets['reading']['sunrise'])); ?></td>

                                <td><span class="symbol sunset"></span></td>
                                <td>Sunset: <?= date("h:iA", strtotime($dataSets['reading']['sunset'])); ?></td>

                                <td><span class="symbol moonphase_<?= $dataSets['reading']['moonphase']['phase_code']; ?>"></span></td>
                                <td>Moon Phase: <?= $dataSets['reading']['moonphase']['phase']; ?></td>
                            </tr>


                        </tbody>
                    </table>
                </div> <!--END CONDITION-->

                <div id="current-reading-table">
                    <table>
                        <tbody>
                            <tr>
                                <th>Wind Speed/Direction</th>
                                <td class="output"><?= $dataSets['reading']['wind_speed']; ?>km/h, <?= $dataSets['reading']['wind_direction']['eng']; ?></td>
                            </tr>
                            <tr>
                                <th>Rain (hourly)</th>
                                <td class="output"><?= $dataSets['reading']['precipitation']; ?>mm</td>
                            </tr>
                            <tr>
                                <th>Relative Humidity</th>
                                <td class="output"><?= $dataSets['reading']['relative_humidity']; ?>%</td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!--END CONDITION TABLE-->


                <?php endIf; ?>

        </div> <!--END CURRENT WEATHER-->
        <?php if ($dataSets['reading']['status'] == 'none'): ?>
            <div class="no-readings" style="display: block;">
                <p>Sorry, there are no readings available for this station.</p>
            </div>
        <?php endif; ?>

        <div id="weekWeather">
            <? if($dataSets['forecast_status'] == 'ok'): ?>
            <!-- CSV FILE <?php echo $dataSets['forecast_dmo_file_csv'];?>-->    
                <?php
                foreach ($dataSets['forecast'] as $key => $dayForecast) {
                    
                    $today = date("Ymd");
                    $tab_class = ($key == $today) ? 'current-tab' : 'tab';
                    $div_id = ($key == $today) ? "Today" : date('l', strtotime($key));

                    $date = date('F j, Y');
                    $divdate = ($key == $date) ? date('F j, Y') : date('F j, Y', strtotime($key));
                    ?>
                    
                    <div class ="forecast-date"><strong><?= $div_id ?></strong><?= ', ' . $divdate ?></div>
                    <table class="forecast-table" cellspacing="0">
                        <tr>
                            <th> Time </th>
                            <th>Condition</th>
                            <th> Temperature </th>
                            <th> Rain</th>
                            <th> Humidity</th>
                            <th> Wind</th>
                        </tr>

                        <?php foreach ($dayForecast as $forecasts2) { ?><!-- <?= $forecasts2['their_time'];?> -->
                            <tr>
                                <td class="time"><?= $forecasts2['localtime_range']; ?></td>
                                <td class="condition"><span class="symbol <?= $forecasts2['weather_condition']['symbol']; ?>" title="<?= $forecasts2['weather_condition']['description']; ?>"></span></td>
                                <td class="temperature"><?= $forecasts2['temperature']; ?>&deg;C</td>
                                <td class="precipitation"><?= $forecasts2['precipitation']; ?>mm</td>
                                <td class="relative-humidity"><?= $forecasts2['relative_humidity']; ?>%</td>
                                <td class="direction"><?php if(trim($forecasts2['wind_direction'])!=''){ ?><span class="symbol <?= $forecasts2['wind_direction']; ?>"></span><?php }?><span class="wind-description"><?= $forecasts2['wind_description']; ?></span></td>
                            </tr>
                        <?php } ?>   

                    </table>
                <?php } ?>
            <? else: ?>
            <div class="no-readings" style="display: block;">
                <p>Sorry, there are no forecast available for this station.</p>
            </div>
            <? endif; ?>    
        </div><!--END WEEK WEATHER-->

    </section> <!--MAIN CONTENT-->

<!-- DISABLED DETAILED FORECAST    -->
<!--    <section class="secondary">
        <div id="charts">
            <h4>Detailed Forecasts</h4>
            <ul class="tabs">
                <li class="temperature flip current-tab"><a href="#charts">Temperature</a></li>
                <li class="precipitation flip"><a href="#charts">Rain</a></li>
                <li class="wind flip"><a href="#charts">Wind</a></li>
                <li class="humidity flip"><a href="#charts">Humidity</a></li>
            </ul>
            <div class="divider"></div>
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
                    <div class="chart-legend">
                        <span style="background-color: red; height: 2px;"></span>Temperature
                        <span style="background-color: green; height: 2px;"></span>Dewpoint
                        <span style="background-color: red; height: 10px; width: 2px; border-radius: 8px;"></span>Highest Temperature
                        <span style="background-color: blue; height: 10px; width: 2px; border-radius: 8px;"></span>Lowest Temperature
                    </div>
                </div>
                <div class="precipitation panel">
                    <script type="text/javascript" language="javascript">
                        //<![CDATA[
                        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                        chart.width = 794;
                        chart.height = 200;
                        chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['stationId']; ?>/precip/6h');
                        chart.write();
                        //]]>
                    </script>
                    <div class="chart-legend">
                        <span style="background-color: blue; height: 5px;"></span>Humidity
                    </div>
                </div>
                <div class="wind panel">
                    <script type="text/javascript" language="javascript">
                        //<![CDATA[
                        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                        chart.width = 794;
                        chart.height = 200;
                        chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['stationId']; ?>/wind/3h');
                        chart.write();
                        //]]>
                    </script>
                    <div class="chart-legend">
                        <span style="background-color: orange; height: 2px;"></span>Wind Gust
                        <span style="background-color: olive; height: 2px;"></span>Wind Speed
                    </div>
                    <script type="text/javascript" language="javascript">
                        //<![CDATA[
                        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                        chart.width = 794;
                        chart.height = 70;
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
                        chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['stationId']; ?>/humidity/3h');
                        chart.write();
                        //]]>
                    </script>
                    <div class="chart-legend">
                        <span style="background-color: green; height: 2px;"></span>Humidity
                    </div>
                </div>
            </div>
        </div>
</div> END CHARTS
        <div id="outlook">
            <h4>15-Day Outlook</h4>
            <ul class="tabs">
                <li class="current-tab"><a href="javascript: void(0);">Temperature</a></li>
                <li><a href="javascript: void(0);">Precipitation</a></li>
                <li><a href="javascript: void(0);">Wind</a></li>
            </ul>
        </div> END OUTLOOK
</section> SECONDARY-->
<!-- END DISABLED DETAILED FORECAST -->
</div> <!--CONTENT-->
