<?php //echo $this->Html->script('AnyChart.js');         ?>

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
        <div class="current-detail-panel shadow">

            <div class="station">
                <h1><?= $dataSets['station_name']; ?></h1>
            </div> <!--END STATION-->

            <?php if ($dataSets['reading']['status'] == 'ok'): ?>
                <h6>Current readings: <?= date("F d, Y h:iA", strtotime($dataSets['reading']['local_time'])); ?></h6>
                <div class="current-detail-condition ">
                    <?php if (!empty($dataSets['reading']['weather_symbol']['symbol']) && $dataSets['reading']['weather_symbol']['symbol'] !='-') { ?>
                        <div class="detail-highlight">
                            <div class="temp-highlight">
                                <?= $dataSets['reading']['temperature']; ?>
                            </div>
                            <div class="condition-highlight">
                                <span class="symbol <?= $dataSets['reading']['weather_symbol']['symbol']; ?>" title="<?= $dataSets['reading']['weather_symbol']['description']; ?>" ></span>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="detail-highlight-alt">
                            <div class="temp-highlight-alt">
                                <?= $dataSets['reading']['temperature']; ?>
                            </div>
                        </div>
                    <?php } ?>

                    <table>
                        <tbody>
                            <tr>
                                <td><span class="symbol sunrise"></span></td>
                                <td>Sunrise: <?= date("h:iA", strtotime($dataSets['sunrise'])); ?></td>

                                <td><span class="symbol sunset"></span></td>
                                <td>Sunset: <?= date("h:iA", strtotime($dataSets['sunset'])); ?></td>

                                <td><span class="symbol moonphase_<?= $dataSets['moonphase']['phase_code']; ?>"></span></td>
                                <td>Moon Phase: <?= $dataSets['moonphase']['phase']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!--END CONDITION-->

                <div class="current-detail-table">
                    <table>
                        <tbody>
                            <?php // if (key_exists('dew_point', $dataSets['reading'])): ?>
                            <!--tr>
                                <th>Dew Point</th>
                                <td><?php // echo $dataSets['reading']['dew_point']; ?></td>
                            </tr-->
                            <?php // endif; // dew point ?>
                            <tr>
                                <th>Wind Speed/Direction</th>
                                <td><span class="symbolwind <?= $dataSets['reading']['wind_direction']; ?>"></span><?= $dataSets['reading']['wind_speed_direction']; ?></td>
                            </tr>
                            <?php if (key_exists('precipitation', $dataSets['reading'])): ?>
                            <tr>
                                <th>Rain</th>
                                <td><?= $dataSets['reading']['precipitation']; ?></td>
                            </tr>
                            <?php endif; // dew point ?>
                            <tr>
                                <th>Relative Humidity</th>
                                <td><?= $dataSets['reading']['relative_humidity']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
                <?php if ($dataSets['reading']['status'] == 'none'): ?>
                    <div class="sun-moon-info">
                        <table>
                            <tbody>
                                <tr>
                                    <td><span class="symbol sunrise"></span></td>
                                    <td class="right-10px">Sunrise: <?= date("h:iA", strtotime($dataSets['sunrise'])); ?></td>

                                    <td><span class="symbol sunset"></span></td>
                                    <td class="right-10px">Sunset: <?= date("h:iA", strtotime($dataSets['sunset'])); ?></td>

                                    <td><span class="symbol moonphase_<?= $dataSets['moonphase']['phase_code']; ?>"></span></td>
                                    <td class="right-10px">Moon Phase: <?= $dataSets['moonphase']['phase']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="no-readings" style="display: block;">
                        <p>Sorry, there are no readings available for this station.</p>
                    </div>
                <?php endif; ?>
                
        </div> <!--END CURRENT WEATHER-->

        <div class="week-forecast">
            <? if ($dataSets['forecast_status'] == 'ok'): ?>
                <!-- CSV FILE <?php echo $dataSets['forecast_dmo_file_csv']; ?>-->
                <?php
                foreach ($dataSets['forecast'] as $key => $dayForecast) {

                    $today = date("Ymd");
                    $tab_class = ($key == $today) ? 'current-tab' : 'tab';
                    $div_id = ($key == $today) ? "Today" : date('l', strtotime($key));

                    $date = date('F j, Y');
                    $divdate = ($key == $date) ? date('F j, Y') : date('F j, Y', strtotime($key));
                    ?>

                    <div class="forecast-date"><strong><?= $div_id ?></strong><?= ', ' . $divdate ?></div>
                    <table>
                        <tr>
                            <th>Time</th>
                            <th>Condition</th>
                            <th>Temperature</th>
                            <th>Rain</th>
                            <th>Humidity</th>
                            <th>Dew Point</th>
                            <th>Wind</th>
                        </tr>

                        <?php foreach ($dayForecast as $forecasts2) { ?><!-- <?= $forecasts2['their_time']; ?> -->
                            <tr>
                                <td><?= $forecasts2['localtime_range']; ?></td>
                                <td><span class="symbol <?= $forecasts2['weather_condition']['symbol']; ?>" title="<?= $forecasts2['weather_condition']['description']; ?>"></span></td>
                                <td><?= $forecasts2['temperature']; ?></td>
                                <td><?= $forecasts2['precipitation']; ?></td>
                                <td><?= $forecasts2['relative_humidity']; ?></td>
                                <td><?= $forecasts2['dew_point']; ?></td>
                                <td><?php if (trim($forecasts2['wind_direction']) != '') { ?><span class="symbol <?= $forecasts2['wind_direction']; ?>"></span><?php } ?><span class="wind-description"><?= $forecasts2['wind_description']; ?></span></td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
            <? else: ?>
                <div class="no-readings" style="display: block;">
                    <p>Sorry, there are no forecast available for this station.</p>
                </div>
            <? endif; ?>
        </div><!--END WEEK FORECAST-->

    </section> <!--MAIN CONTENT-->

    <!-- DISABLED DETAILED FORECAST    -->
    <!--    <section class="secondary">
            <div id="charts">
                <h4>Detailed Forecasts</h4>
                <ul class="tabs">
                    <li class="temperature flip current-tab"><a href="#charts">Temperature</a></li>
                    <li class="flip"><a href="#charts">Rain</a></li>
                    <li class="flip"><a href="#charts">Wind</a></li>
                    <li class="flip"><a href="#charts">Humidity</a></li>
                </ul>
                <div class="divider"></div>
                <div class="tab-container">
                    <div class="panel current-tab">
                        <script type="text/javascript" language="javascript">
                            //<![CDATA[
                            var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                            chart.width = 794;
                            chart.height = 200;
                            chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['station_id']; ?>/temperature/3h');
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
                    <div class="panel">
                        <script type="text/javascript" language="javascript">
                            //<![CDATA[
                            var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                            chart.width = 794;
                            chart.height = 200;
                            chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['station_id']; ?>/precip/6h');
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
                            chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['station_id']; ?>/wind/3h');
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
                            chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['station_id']; ?>/winddir/6h');
                            chart.write();
                            //]]>
                        </script>
                    </div>
                    <div class="panel">
                        <script type="text/javascript" language="javascript">
                            //<![CDATA[
                            var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                            chart.width = 794;
                            chart.height = 200;
                            chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['station_id']; ?>/humidity/3h');
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
