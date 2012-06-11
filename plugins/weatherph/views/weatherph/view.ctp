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
            <ul id="week-forecast" class="tabs">
                
            <?php
            $i = 0; 
            foreach ($forecastRange as $day) {
                $class = ($i == 0)? "current-tab" : "";
                $dayName = ($i == 0)? "Today" : date('l', strtotime($day));
                $i++;
            ?>
                <li id="<?= strtolower($dayName); ?>" class="<?= $class; ?>" ><?= $dayName; ?></li>
            <?php } ?>
            </ul>
            
            <div class="tab-container">
            <?php foreach ($dataSets['forecast'] as $key => $dayForecast) {
                
                $today = date("Ymd");
                $tab_class = ($key == $today)? 'current-tab' : 'tab';
                $div_id = ($key == $today)? "Today" : date('l', strtotime($key));
                
                ?>
                <div id="<?= strtolower($div_id); ?>" class="<?= $tab_class; ?>">
                    
                    <table class="week-forecast" cellspacing="0">
                    <tr class="time">
                        <td class="caption">Time</td>
                        <?php foreach (Set::extract($dayForecast, '{n}.localtime_range') as $column) { ?>
                            <td><?= $column; ?></td>
                        <?php } ?>
                    </tr>
                    
                    <tr class="condition">
                        <td class="caption">Condition</td>
                        <?php foreach (Set::extract($dayForecast, '{n}.weather_symbol') as $column) { ?>
                            <td><span class="symbol <?= $column; ?>"></span></td>
                        <?php } ?>
                    </tr>
                    
                    <tr class="temperature">
                        <td class="caption">Temperature</td>   
                        <?php foreach (Set::extract($dayForecast, '{n}.temperature') as $column) { ?>
                            <td><?= $column; ?>&deg;C</td>
                        <?php } ?>
                    </tr>
                    
                    <tr class="precipitation">
                        <td class="caption">Precipitation</td> 
                        <?php foreach (Set::extract($dayForecast, '{n}.precipitation') as $key=>$column) { ?>
                        <?php if($key%2 == 0){ ?><td colspan="2"><?= $column; ?>mm</td><?php } ?>
                        <?php } ?>
                    </tr>
                    
                    <tr class="wind">
                        <td class="caption">Wind speed</td>   
                        <?php foreach (Set::extract($dayForecast, '{n}.wind_speed') as $column) { ?>
                            <td><?= $column; ?>km/h</td>
                        <?php } ?>
                    </tr>
                    
                    <tr class="direction">
                        <td class="caption">Wind Direction</td>   
                        <?php $windDir = Set::extract($dayForecast, '{n}.wind_direction'); ?>
                        <?php $windDesc = Set::extract($dayForecast, '{n}.wind_description'); ?>
                        <?php for($x=0; $x<count($windDir); $x++){ ?>
                            <td><span class="symbol <?= $windDir[$x]; ?>"></span><span class="wind-description"><?= $windDesc[$x]?></span></td>
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