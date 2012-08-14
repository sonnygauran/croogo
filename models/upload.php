<?php

class Upload extends AppModel{
    
    var $useTable = false;
    var $name = 'Upload';

 
    public function printTag($string){
        return htmlentities('<') . $string . htmlentities('>') . "\n";
    }
}
