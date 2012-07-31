<?php

class ResourcesController extends WeatherphAppController {

    // return the browser request header
// use built in apache ftn when PHP built as module,
// or query $_SERVER when cgi
    function getRequestHeaders() {
        if (function_exists("apache_request_headers")) {
            if ($headers = apache_request_headers()) {
                return $headers;
            }
        }
        $headers = array();
        // Grab the IF_MODIFIED_SINCE header
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $headers['If-Modified-Since'] = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
        }
        return $headers;
    }

    public function data_layer($name, $hash) {
        $headers = $this->getRequestHeaders();
        $this->log($headers);

            $this->layout = 'image/png';

//
//        App::import('Model', 'Resource');
//        $Resource = new Resource();
//        $resource = $Resource->findByHash($hash);
//        $validResource = $Resource->obtain(__FUNCTION__, $name, $hash);
//
//        $lon = $_GET['lon'];
//        $lat = $_GET['lat'];
//
//        $bbox = array();
//        $bbox[] = 'x1=' . $lon[0];
//        $bbox[] = 'x2=' . $lon[1];
//        $bbox[] = 'y1=' . $lat[1];
//        $bbox[] = 'y2=' . $lat[0];
//
//        $assetLocation = $validResource['Resource']['value'] . implode('&', $bbox);

//$temperature_url = dirname(dirname(dirname(dirname(__FILE__))))."/data/images/all_tempertature.png";
//$pressure_url = dirname(dirname(dirname(dirname(__FILE__))))."/data/images/all_pressure.png";
//error_log($temperature_url);
//header('Content-type: image/png');
//
//switch($name){
//    case 'pressure':
//        echo file_get_contents($pressure_url);
//        break;
//    case 'temperature':
//        echo file_get_contents($temperature_url);
//
//        break;
//}

        $gum = implode('_', array(__FUNCTION__, $name, sha1($validResource['Resource']['value']), implode('_', array(implode('_', $lon), implode('_', $lat)))));
        $this->log($gum);
        $oxygen = 'hourly';

        $result = NULL;
        if (!Cache::read($gum, $oxygen)) {
            $this->log('not');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $assetLocation);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //        curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
            curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Client 1.0");
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            $result = curl_exec($ch);
            curl_close($ch);

            Cache::write($gum, $result, $oxygen);
        } else {
            $this->log('exists');
            $result = Cache::read($gum, $oxygen);
        }
        $resource = $result;
//
//            $this->log(print_r(compact('assetLocation'), true));
//            $resource = file_get_contents($assetLocation);

        $this->set(compact('resource'));
    }
}