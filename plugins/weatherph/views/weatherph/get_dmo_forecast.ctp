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
                <h1><?= $location['Name']['full_name_ro']; ?></h1>
                <h6>Current readings</h6>
                <!-- <a href="#">change station</a>-->
                <div id="nearest-station">Nearest Station: <?= $dataSets['station_name']; ?> (<?= $distance ?>)</div>
            </div> <!--END STATION-->

            <?php if ($dataSets['reading']['status'] == 'ok'): ?>
                <div class="current-detail-condition ">
                    <?php if (!empty($dataSets['reading']['weather_symbol']['symbol'])) { ?>
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
                                <td><span class="symbol <?= $forecasts2['weather_symbol']['symbol']; ?>" title="<?= $forecasts2['weather_symbol']['description']; ?>"></span></td>
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
    
</div> <!--CONTENT-->