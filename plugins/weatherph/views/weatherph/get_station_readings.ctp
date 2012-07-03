<?php

//echo "<pre>";
//print_r($readings);
//echo "</pre>";

?>
<table>
    <thead>
        <tr>
            <th>Date/Time</th>
            <th>Condition</th>
            <th>Temperature</th>
            <th>Rain</th>
            <th>Humidity</th>
            <th>Wind Speed</th>
            <th>Wind Gust</th>
            <th>Wind Direction</th>
        </tr>
    </thead>
    
    <tbody>
<?php
    foreach($readings as $reading){
?>
        <tr>
            <td><?= $reading['date_time']; ?></td>
            <td><?= $reading['weather_condition']; ?></td>
            <td><?= $reading['temperature']; ?></td>
            <td><?= $reading['rain']; ?></td>
            <td><?= $reading['humidity']; ?></td>
            <td><?= $reading['wind_speed']; ?></td>
            <td><?= $reading['wind_gust']; ?></td>
            <td><?= $reading['wind_direction']; ?></td>
            <td><?= $reading['global_radiation']; ?></td>
        </tr>
<?php 
    }
?>
        
    </tbody>
</table>

