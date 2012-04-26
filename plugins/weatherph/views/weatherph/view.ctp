<script type="text/javascript" src="../weatherph/js/weatherph/view.js"></script>

<div id="content">
    <section class="main">
        <div id="currentWeather">
            <div id="station">
                <h1><?= $weeklyForecasts['ort1']; ?></h1>
                <br/><br/>
                <p>Current readings from:</p>
                <h6>Salcedo weather station</h6>
                <p>change station</p>
            </div> <!--END STATION-->

            <div id="condition">
                <img src="/theme/weatherph_detail/img/cloudy1.png"/>
                <div class="condition-text"> 
                    <h3>Partly Cloudy</h3>
                    <h2><?= $weeklyForecasts['reading']['tl']; ?>&deg;C</h2>
                    <br/>
                    <ul>
                        <li>Sunrise: <?= date("h:iA",strtotime($weeklyForecasts['reading']['sunrise'])); ?></li>
                        <li>Sunset: <?= date("h:iA",strtotime($weeklyForecasts['reading']['sunset'])); ?></li>
                        <li>Moon: <?= $weeklyForecasts['reading']['moonphase']['phase']; ?></li>
                    </ul>
                </div> <!--END CONDITON TEXT-->
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
                <li id="<?= strtolower($dayName); ?>" class="<?= $class; ?>" ><a href="javascript: void();"><?= $dayName; ?></a></li>
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
                <div id="<?=strtolower($div_id);?>" class="<?= $tab_class; ?>">
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
                        <td><?= $column; ?>&percnt;</td>
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
                            <td><?php if($column != ''){ echo $this->Html->image('w'.$column.'.png', array("width"=>'27px')); }else{ ?>-<?php } ?></td>
                        <?php } ?>
                    </tr>
           
                    </table>
                </div>
            <?php } ?>
                
        </div> <!--END WEEK WEATHER-->

    </section> <!--MAIN CONTENT-->

    <section class="secondary">
        <div id="charts">
            <h4>Detailed Forecasts</h4>
            <ul class="tabs">
                <li class="current-tab"><a href="#">Temperature</a></li>
                <li><a href="#">Precipitation</a></li>
                <li><a href="#">Wind</a></li>
                <li><a href="#">Humidity</a></li>
            </ul>
            <div class="tab-container">
                <div class="current-tab">
                    <script type="text/javascript" language="javascript"> 
                    //<![CDATA[
                    AnyChart.renderingType = anychart.RenderingType.SVG_ONLY; 
                    var chart = new AnyChart();
                    chart.width = 554;
                    chart.height = 300;
                    chart.setXMLFile('/anychart.xml');
                    chart.write();
                    //]]>
                    </script>
                </div>
            </div>
        </div> <!--END CHARTS-->

        <div id="outlook">
            <h4>15-Day Outlook</h4>
            <ul class="tabs">
                <li class="current-tab"><a href="#">Temperature</a></li>
                <li><a href="#">Precipitation</a></li>
                <li><a href="#">Wind</a></li>
            </ul>
            <?php echo $this->Html->image('chart.png'); ?>
        </div> <!--END OUTLOOK-->
    </section> <!--SECONDARY-->
</div> <!--CONTENT-->