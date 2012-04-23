<?php
foreach($forecasts as $keyStationName => $forecast){
    $stationName = explode('/', $keyStationName);
?>   

    <div id="wrapper">
        <h1><?= $stationName[1]; ?></h1>
        
        <table class="forecast-labels">
            <tr><td>&nbsp;</td></tr>
            <tr><td>Time of Day</td></tr>
            <tr><td>Symbol</td></tr>
            <tr><td>Temperature (<sup>o</sup>C)</td></tr>
            <tr><td>Precipitation (l/m<sup>2</sup>)</td></tr>
            <tr><td>Relative Humidity</td></tr>
            <tr><td>Wind Speed</td></tr>
            <tr><td>Wind Guts (km/h)</td></tr>
            <tr><td>Wind Direction</td></tr>
        </table>
        
        <div id="forecast-wrapper">
            <div id="tables-wrapper">

<?php foreach($forecast as $keyDatum => $dailyForecast){ ?>
        <table>
            <thead>
            <th colspan="8"><?= date('l, d.m.Y', strtotime($keyDatum)) ?></th>
            </thead>
            <tbody>
                <tr>
                <?php foreach (Set::extract($dailyForecast, '{n}.utch') as $column) { ?>
                    <td><?= $column; ?></td>
                <?php } ?>
                </tr>
                <tr>
                    
                <?php foreach (Set::extract($dailyForecast, '{n}.sy') as $column) { ?>
                    <td class="symbol <?= $column; ?>" >&nbsp;</td>
                <?php } ?>
                </tr>
                <tr>
                <?php foreach (Set::extract($dailyForecast, '{n}.tl') as $column) { ?>
                        <td><?= $column; ?></td>
                <?php } ?>
                </tr>
                <tr>
                <?php foreach (Set::extract($dailyForecast, '{n}.rr') as $column) { ?>
                        <td><?= $column; ?></td>
                <?php } ?>
                </tr>
                <tr>
                <?php foreach (Set::extract($dailyForecast, '{n}.rh') as $column) { ?>
                        <td><?= $column; ?></td>
                <?php } ?>
                </tr>
                <tr>
                <?php foreach (Set::extract($dailyForecast, '{n}.ff') as $column) { ?>
                        <td><?= $column; ?></td>
                <?php } ?>
                </tr>
                <tr>
                <?php foreach (Set::extract($dailyForecast, '{n}.g3h') as $column) { ?>
                        <td><?= $column; ?></td>
                <?php } ?>
                </tr>
                <tr>  
                <?php foreach (Set::extract($dailyForecast, '{n}.dir') as $column) { ?>
                        <td><?php 
                                if($column != ''){
                                    echo $this->Html->image('w'.$column.'.png', array("width"=>'27px'));
                                }else{ ?>
                                -
                          <?php } ?></td>
                <?php } ?>
                </tr>
            </tbody>
        </table>
    <?php } ?>
       </div>
   </div>
</div>

<?php } ?>