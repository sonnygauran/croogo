<?php 
//echo '<pre>';
//print_r($readings);
//echo '</pre>';
?>

<script type="text/javascript" src="<?= $this->webroot ?>weatherph/js/weatherph/widget_readings.js"></script>
<style type="text/css">
    
    div.readings { display: none; }
    div.readings.current { display: block; }
    
    ul.tabs-menu { display: block; clear: both;}
    ul.tabs-menu li.current { background-color: #393A3B; color: #FFF; }
    ul.tabs-menu li { 
            display: inline-block; 
            background-color: #7A7C7D; 
            margin: 0; 
            padding: 10px 20px; 
            font-size: 14px; 
            font-weight: bold; 
            cursor: pointer;
            color: #BEC1C4;
            -moz-border-radius-topright: 10px;
            border-top-right-radius: 10px;
            -moz-border-radius-topleft: 10px;
            border-top-left-radius: 10px;
    }
    
    #measurements-tbl { font-size: 12px; display: block; clear: both; }
    #measurements-tbl th { background-color: #eee; padding: 5px 10px; }
    #measurements-tbl td { padding: 5px 10px; border-bottom: 1px solid; }
</style>

<h2><?php if(isset($readings['station_info']['name'])) echo $readings['station_info']['name']; else echo "Station Not Found!"; ?></h2>    

<?php
if(key_exists('readings', $readings)){    
?>    
<ul class="tabs-menu">
<?php
$cntr = 0;
foreach($readings['readings'] as $day=>$reading){
    $cntr++;
    $current_class = ($cntr == 1)? 'current' : 'tabs';
?>
    <li id="<?= strtotime($day);?>" class="<?= $current_class; ?>" ><?= date('Ymd', strtotime($day)); ?></li>
<?php    
}
?>
</ul>
<?php    
    $cntr = 0;
    foreach($readings['readings'] as $day=>$reading){
        $cntr++;
        $current_class = ($cntr == 1)? 'current' : '';
?>

<div id="mtbl-<?= strtotime($day); ?>" class="readings <?= $current_class; ?>">
    <table id="measurements-tbl">
        <thead>
            <tr>
                <th>Date/Time</th>
                <th>Condition</th>
                <th>Temperature</th>
                <th>Dew Point</th>
                <th>Rain(1H)</th>
                <th>Rain(6H)</th>
                <th>Humidity</th>
                <th>Wind Speed</th>
                <th>Wind Gust</th>
                <th>Wind Direction</th>
                <th>Global Radiation</th>
            </tr>
        </thead>

        <tbody>
    <?php   
            foreach($reading as $data){
    ?>
            <tr>
                <td><?= $data['date_time']; ?></td>
                <td><?= $data['weather_condition']; ?></td>
                <td><?= $data['temperature']; ?></td>
                <td><?= $data['dew_point']; ?></td>
                <td><?= $data['rain1h']; ?></td>
                <td><?= $data['rain6h']; ?></td>
                <td><?= $data['humidity']; ?></td>
                <td><?= $data['wind_speed']; ?></td>
                <td><?= $data['wind_gust']; ?></td>
                <td><?= $data['wind_direction']; ?></td>
                <td><?= $data['global_radiation']; ?></td>
            </tr>
    <?php
            }
    ?>
        </tbody>
    </table>
</div>
<?php        
    }
}else{
?>
<table id="measurements-tbl">
    <thead>
        <tr>
            <th>Date/Time</th>
            <th>Condition</th>
            <th>Temperature</th>
            <th>Dew Point</th>
            <th>Rain</th>
            <th>Humidity</th>
            <th>Wind Speed</th>
            <th>Wind Gust</th>
            <th>Wind Direction</th>
            <th>Global Radiation</th>
        </tr>
    </thead>
    
    <tbody>        
        <tr>
            <td colspan="10" align="center">Station Readings not found!.</td>
        </tr>
    </tbody>
</table>
<?php 
}
?>  