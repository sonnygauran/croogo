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

            <?php if(!empty($dataSets['reading']['ort1'])): ?>
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
        <?php if(empty($dataSets['readings'])): ?>
            <div class="no-readings" style="display: block;">
                <p>Sorry, there are no readings available for this station.</p>
            </div>
        <?php endIf; ?>
        
        <div class="no-forecast">
            <p>Sorry, there's no forecast available for this station right now. Please try another station.</p>
        </div>

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
            <?php foreach ($dataSets['forecast'] as $key => $dayForecast) {
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
                            var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                            chart.width = 830;
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
                            chart.width = 830;
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
                            chart.width = 830;
                            chart.height = 200;
                            chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $dataSets['stationId']; ?>/wind');
                            chart.write();
                        //]]>
                    </script>
                  <script type="text/javascript" language="javascript">
                        //<![CDATA[
                            var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
                            chart.width = 830;
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
                            chart.width = 830;
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