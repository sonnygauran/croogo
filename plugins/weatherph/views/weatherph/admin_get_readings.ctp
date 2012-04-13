<?php 

//echo '<pre>';
//print_r($two_week_readings); 
//echo '</pre>';

foreach($date_readings as $keyId=>$station){
    
    $station_name = explode('/', $keyId);
    
    echo '<table style="margin: 50px 0 50px 0;">'; 
    echo '<tr><td colspan="17" class="stations_name" stye="font-size: 18pt;">'.$station_name[1].' - ' .$station_name[0]. '</td></tr>';
    
    foreach($station as $keyDate=>$readingDates){
        
        echo '<tr><td colspan="17">'.date('F d, Y', strtotime($keyDate)).'</td></td>';
        
        echo '<tr>
                <th>UTC</th>
                <th>DIR</th>
                <th>FF</th>
                <th>TL</th>
                <th>TD</th>
                <th>TX</th>
                <th>TN</th>
                <th>QFF</th>
                <th>AP</th>
                <th>WWW</th>
                <th>METARWX</th>
                <th>VIS</th>
                <th>COV</th>
                <th>N</th>
                <th>L</th>
                <th>CLCMCH</th>
                <th>CLG</th>
              </tr>';
        
        foreach ($readingDates as $keyHourly=>$readingHourly){
            
            echo '<tr>
                    <td>'.$keyHourly.':'.$readingHourly['min'].'</td>
                    <td>'.$readingHourly['dir'].'</td>
                    <td>'.$readingHourly['ff'].'</td>
                    <td>'.$readingHourly['tl'].'</td>
                    <td>'.$readingHourly['td'].'</td>
                    <td>'.$readingHourly['tx'].'</td>
                    <td>'.$readingHourly['tn'].'</td>
                    <td>'.$readingHourly['qff'].'</td>
                    <td>'.$readingHourly['ap'].'</td>
                    <td>'.$readingHourly['www'].'</td>
                    <td>'.$readingHourly['metarwx'].'</td>
                    <td>'.$readingHourly['vis'].'</td>
                    <td>'.$readingHourly['cov'].'</td>
                    <td>'.$readingHourly['n'].'</td>
                    <td>'.$readingHourly['l'].'</td>
                    <td>'.$readingHourly['clcmch'].'</td>
                    <td>'.$readingHourly['clg'].'</td>
                 </tr>';
            
        }
        
    }
    
    echo '</table>';
    
}

?>