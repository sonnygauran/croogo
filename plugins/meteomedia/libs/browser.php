<?php

class Browser{
    
    private $extension;
    private $codec;
    private $browser = array(
            'version'   => '0.0.0',
            'majorver'  => 0,
            'minorver'  => 0,
            'build'     => 0,
            'name'      => 'unknown',
            'useragent' => ''
        );
    
    public static $instance;
    
    public function __construct() {
        $this->detect_browser();
        $this->detect_extension();
    }
    
    public static function getInstance(){
        if(empty(self::$instance)) self::$instance = new Browser();
        return self::$instance;
    }
    
    private function detect_browser(){

        $browsers = array(
            'firefox', 'msie', 'opera', 'chrome', 'safari', 'mozilla', 'seamonkey', 'konqueror', 'netscape',
            'gecko', 'navigator', 'mosaic', 'lynx', 'amaya', 'omniweb', 'avant', 'camino', 'flock', 'aol'
        );

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->browser['useragent'] = $_SERVER['HTTP_USER_AGENT'];
            $user_agent = strtolower($this->browser['useragent']);
            foreach($browsers as $_browser) {
                if (preg_match("/($_browser)[\/ ]?([0-9.]*)/", $user_agent, $match)) {
                    $this->browser['name'] = $match[1];
                    $this->browser['version'] = $match[2];
                    @list($this->browser['majorver'], $this->browser['minorver'], $this->browser['build']) = explode('.', $this->browser['version']);
                    break;
                }
            }
        }
        
    }
    
    private function detect_extension(){
        switch($this->browser['name']){
            case 'firefox':
            case 'konqueror':
            case 'chrome':
            case 'opera':
                $this->extension = 'webm';
                $this->codec = 'video/webm;';
                break;
            case 'safari':
                $this->extension = 'm4v';
                $this->codec = 'video/x-m4v;';
                break;
            case 'msie':
                $this->extension = 'mp4';
                $this->codec = 'video/mp4;';
                break;
        }
        
    }
    
    public function info(){
        return $this->browser;
    }
    
    public function extension(){
        return $this->extension;
    }
    
    public function codec(){
        return $this->codec;
    }
}