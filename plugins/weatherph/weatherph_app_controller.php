<?php
class WeatherphAppController extends AppController {
    public function beforeFilter() {
        $this->layout = 'weatherph';
    }
}