<?php 

if(is_array($outputData)){
    print_r($outputData);
}else{
    echo $outputData->asXML(); 
}
?>
