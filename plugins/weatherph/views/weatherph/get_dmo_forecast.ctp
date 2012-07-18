<?php //echo $this->Html->script('AnyChart.js');       ?>

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
        <div id="current-weather" class="shadow">

            <div id="station">
                <h1><?= $location['Name']['full_name_ro']; ?></h1>
                <h6>Current readings</h6>
                <!-- <a href="#">change station</a>-->
                <div id="nearest-station">Nearest Station: <?= $dataSets['station_name']; ?> (<?= $distance ?>)</div>
            </div> <!--END STATION-->

            <?php if ($dataSets['reading']['status'] == 'ok'): ?>

                <div id="condition">

                    <?php if (!empty($dataSets['reading']['weather_symbol']['symbol'])) { ?>
                        <div class="inner-condition">
                            <div class="left-temp-reading">
                                <?= $dataSets['reading']['temperature']; ?>
                            </div>

                            <div class="right-sy-reading">
                                <span class="symbol <?= $dataSets['reading']['weather_symbol']['symbol']; ?>" title="<?= $dataSets['reading']['weather_symbol']['description']; ?>" ></span>
                            </div>
                        </div>    
                    <?php } else { ?>
                        <div class="inner-condition-temp-reading-only">
                            <div class="reading-temperature-only">

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

                <div id="current-reading-table">
                    <table>
                        <tbody>
                            <?php if (array_key_exists('dew_point', $dataSets['reading'])): ?>
                            <tr>
                                <th>Dew Point</th>
                                <td><?= $dataSets['reading']['dew_point']; ?></td>
                            </tr>
                            <?php endif; // dew point ?>
                            <tr>
                                <th>Wind Speed/Direction</th>
                                <td><span class="symbolwind <?= $dataSets['reading']['wind_direction']; ?>" title="<?= $dataSets['reading']['wind_speed']; ?>, <?= $dataSets['reading']['wind_description']['eng']; ?>"></span><?= $dataSets['reading']['wind_speed']; ?>, <?= $dataSets['reading']['wind_description']['eng']; ?></td>
                            </tr>
                            <tr>
                                <th>Rain</th>
                                <td><?= $dataSets['reading']['precipitation']; ?></td>
                            </tr>
                            <tr>
                                <th>Relative Humidity</th>
                                <td><?= $dataSets['reading']['relative_humidity']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!--END CONDITION TABLE-->
            <?php endif; ?>
                
            <?php if ($dataSets['reading']['status'] == 'none'): ?>
                <div id="sun-moon-info">
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
            <?php endIf; ?>

        </div> <!--END CURRENT WEATHER-->
            
        <div id="week-forecast">
            <? if ($dataSets['forecast_status'] == 'ok'): ?>
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

                        <div class ="forecast-date"><span class="daytime"><?= $div_id ?></span><?= ', ' . $divdate ?></div>
                        <table class="forecast-table" cellspacing="0">
                            <tr>
                                <th class="columnheader">Time</th>
                                <th class="columnheader">Condition</th>
                                <th class="columnheader">Temperature</th>
                                <th class="columnheader">Dew Point</th>
                                <th class="columnheader">Rain</th>
                                <th class="columnheader">Humidity</th>
                                <th class="columnheader">Wind</th>
                            </tr>

                            <?php foreach ($dayForecast as $forecasts2) { ?><!-- <?= $forecasts2['their_time']; ?> -->
                                <tr>
                                    <td class="time"><?= $forecasts2['localtime_range']; ?></td>
                                    <td class="condition"><span class="symbol <?= $forecasts2['weather_symbol']['symbol']; ?>" title="<?= $forecasts2['weather_symbol']['description']; ?>"></span></td>
                                    <td class="temperature"><?= $forecasts2['temperature']; ?></td>
                                    <td class="dew_point"><?= $forecasts2['dew_point']; ?></td>
                                    <td class="precipitation"><?= $forecasts2['precipitation']; ?></td>
                                    <td class="relative-humidity"><?= $forecasts2['relative_humidity']; ?></td>
                                    <td class="direction"><?php if (trim($forecasts2['wind_direction']) != '') { ?><span class="symbol <?= $forecasts2['wind_direction']; ?>"></span><?php } ?><span class="wind-description"><?= $forecasts2['wind_description']; ?></span></td>
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

</div> <!--CONTENT-->