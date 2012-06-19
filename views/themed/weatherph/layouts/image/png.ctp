<?php

$expiry = '2 days';
if (isset($expiry_for_layout)) {
    $expiry = $expiry_for_layout;
}


//    // Image not cached or cache outdated, we respond '200 OK' and output the image.
//    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT', true, 200);
//    header('Content-type: image/png');
//    header('Content-transfer-encoding: binary');
//    header('Content-length: '.filesize($graphicFileName));
//    readfile($graphicFileName);

$this->log($expiry);
header("Cache-Control: private, max-age=10800, pre-check=10800");
header("Pragma: private");
header("Expires: " . date(DATE_RFC822,strtotime($expiry)));
header("Content-Type: image/png");
// the browser will send a $_SERVER['HTTP_IF_MODIFIED_SINCE'] if it has a cached copy 
if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
    // if the browser has a cached version of this image, send 304
    header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'],true,304);
    //exit;
}
echo $content_for_layout;