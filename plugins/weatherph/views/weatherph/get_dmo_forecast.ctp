<?php //echo $this->Html->script('AnyChart.js');      ?>

<?php
//echo $this->Html->script(array(
//    $this->webroot.'weatherph/js/weatherph/view',
//    $this->webroot.'weatherph/js/weatherph/AnyChart',
//    )); 

echo $this->Html->script(array(
    $this->webroot . 'weatherph/js/weatherph/view',
    $this->webroot . 'weatherph/js/weatherph/AnyChart',
));
?>

<!--<script type="text/javascript" scr="<?= $this->webroot ?>js/weatherph/view.js"></script>
<script type="text/javascript" scr="<?= $this->webroot ?>js/weatherph/AnyChart.js"></script>-->

<div class="content">
    <section class="main">
        <div id="currentWeather" class="shadow">

            <div id="station">
                <h1><?= $dataSets['stationName']; ?></h1>

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

                <div id="conditionTable">
                    <table>
                        <tbody>

                            <tr>
                                <td class="caption">Avg. Wind Speed</td>
                                <td class="output"><?= $dataSets['reading']['wind_speed']; ?>km/h</td>
                            </tr>
                            <tr>
                                <td class="caption">Precipitation</td>
                                <td class="output"><?= $dataSets['reading']['precipitation']; ?>mm</td>
                            </tr>
                            <tr>
                                <td class="caption">Relative Humidity</td>
                                <td class="output"><?= $dataSets['reading']['relative_humidity']; ?>%</td>
                            </tr>
                            <tr>
                                <td class="caption">Wind Direction</td>
                                <td class="output"><?= $dataSets['reading']['wind_direction']['eng']; ?></td>                        
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
            <?php endIf; ?>

        <div id="weekWeather">
            <? if($dataSets['forecast_status'] == 'ok'): ?>
            <!-- CSV FILE: <?= $dataSets['forecast_dmo_file_csv']; ?> -->
            <div class="tab-container">
                <?php
                foreach ($dataSets['forecast'] as $key => $dayForecast) {

                    $today = date("Ymd");
                    $tab_class = ($key == $today) ? 'current-tab' : 'tab';
                    $div_id = ($key == $today) ? "Today" : date('l', strtotime($key));

                    $date = date('F j, Y');
                    $divdate = ($key == $date) ? date('F j, Y') : date('F j, Y', strtotime($key));
                    ?>

                    <div class ="daydate"><span class="daytime"><?= $div_id ?></span><?= ', ' . $divdate ?></div>
                    <table class="week-forecast" cellspacing="0">
                        <tr>
                            <th class="columnheader"> Time </th>
                            <th class="columnheader">Condition</th>
                            <th class="columnheader"> Temperature </th>
                            <th class="columnheader"> Precipitation </th>
                            <th class="columnheader"> Humidity</th>
                            <th class="columnheader"> Wind</th>
                        </tr>

                        <?php foreach ($dayForecast as $forecasts2) { ?><!-- <?= $forecasts2['their_time'];?> -->
                            <tr>
                                <td class="time"><?= $forecasts2['localtime_range']; ?></td>
                                <td class="condition"><span class="symbol <?= $forecasts2['weather_symbol']['symbol']; ?>" title="<?= $forecasts2['weather_symbol']['description']; ?>"></span></td>
                                <td class="temperature"><?= $forecasts2['temperature']; ?>&deg;C</td>
                                <td class="precipitation"><?= $forecasts2['precipitation']; ?>mm</td>
                                <td class="relative-humidity"><?= $forecasts2['relative_humidity']; ?>%</td>
                                <td class="direction"><?php if(trim($forecasts2['wind_direction'])!=''){ ?><span class="symbol <?= $forecasts2['wind_direction']; ?>"></span><?php }?><span class="wind-description"><?= $forecasts2['wind_description']; ?></span></td>
                            </tr>
                        <?php } ?>   

                    </table>
                <?php } ?>
            </div>
            <? else: ?>
            <div class="no-readings" style="display: block;">
                <p>Sorry, there are no forecast available for this station.</p>
            </div>
            <? endif; ?>
        </div><!--END WEEK WEATHER-->

    </section> <!--MAIN CONTENT-->

    <!--section class="secondary">
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
                    <div class="legend"><span class="red-line"></span>Temperature <span class="green-line"></span>Dewpoint</div>
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
                </div>
            </div>
        </div> <!--END CHARTS-->
    <!--/section--> <!--SECONDARY-->
</div> <!--CONTENT-->