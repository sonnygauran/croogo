<?php
/**
 * index.js requires the following variable:
 *      - resource - contains an array of (data-layer => (temperature, pressure)) for retreiving the image key. 
 */
?>
<script type="text/javascript">
    window["DATA_LAYER"] = null;
    window["DATA_LAYERS"] = <?= json_encode($resources['data-layers']); ?>;

    var windContent = '<?= str_replace("\n", "\\", (<<<ECHO
        <video id="movie-wind" width="554" height="554" controls="controls">
        <source src="{$this->webroot}assets/theme/weatherph/vid/wind.mp4" type="video/mp4" />
        <source src="{$this->webroot}assets/theme/weatherph/vid/wind.webm" type="video/webm" />
        Your browser does not support the video tag.
        </video>
ECHO
));
        ?>';
    var precipContent = '<?= str_replace("\n", "\\", (<<<ECHO
        <video id="movie-precipitation" width="554" height="554" controls="controls">
        <source src="{$this->webroot}assets/theme/weatherph/vid/precip.mp4" type="video/mp4" />
        <source src="{$this->webroot}assets/theme/weatherph/vid/precip.webm" type="video/webm" />
        Your browser does not support the video tag.
        </video>
ECHO
)); ?>';
        
    window['MOVIE_CONTENT'] = {
        wind:          windContent,
        precipitation: precipContent
    };
</script>
<script type="text/javascript" src="<?= $this->webroot ?>weatherph/js/weatherph/index.js"></script>

<style type="text/css">
    .loader {
        background: white url('<?= $this->webroot ?>theme/weatherph/img/loader-twirl.gif') no-repeat center center;
        width: 100%; height: 100%; display: block; visibility: none;
    }
    .loader-img {
        margin-top: 275px;
        margin-left: 275px;
    }
</style>
<div class="content">
    <section class="main cf">
        <div id="map-container">
            <div class="layerSelector cf">
                <ul class="movies data-layers dropdown">
                    <li><a href="#" data-target="data-layer" data-name="stations">Weather stations</a></li>
                    <li>
                        <a href="#">Weather movies</a>
                        <ul>
                            <li><a href="#map" data-type="movie" data-name="wind"          data-target="movie-wind">Wind</a></li>
                            <li><a href="#map" data-type="movie" data-name="precipitation" data-target="movie-precip">Rain</a></li>
                        </ul>
                    </li>
                    <li><a data-target="data-layer" data-name="temperature">Temperature</a></li>
                    <li><a data-target="data-layer" data-name="pressure">Pressure</a></li>
                </ul>
            </div> <!--LAYER SELECTOR-->
            <div class="video-viewport">

            </div>
            <div class="map-viewport">
                <div class="data-layer" style="width: 554px; height: 554px; float:left" data-bbox="">
                </div>
                <div id="map">
                    <div class="loader">
                    </div>
                </div>
            </div>
            <div id="legend" class="shadow">
                <div class="scale-temperature">
                    <div class="scale-celsius">
                        <ul class="scale-upper">
                            <li style="background-color: #FFC8C8;">46</li>
                            <li style="background-color: #FFA0A0;">44</li>
                            <li style="background-color: #FF82B4;">42</li>
                            <li style="background-color: #FF5096;">40</li>
                            <li style="background-color: #FF0064;">38</li>
                            <li style="background-color: #D2005A;">36</li>
                            <li style="background-color: #A50041;">34</li>
                            <li style="background-color: #780000;">32</li>
                            <li style="background-color: #A00000;">30</li>
                            <li style="background-color: #C30000;">28</li>
                            <li style="background-color: #E60000;">26</li>
                            <li style="background-color: #FF5A00;">24</li>
                            <li style="background-color: #FF8200;">22</li>
                            <li style="background-color: #FFA000;">20</li>
                            <li style="background-color: #FFBE00;">18</li>
                            <li style="background-color: #FFD200;">16</li>
                            <li style="background-color: #FFE600;">14</li>
                            <li style="background-color: #FFFF14;">12</li>
                            <li style="background-color: #C8FF00;">10</li>
                            <li style="background-color: #96FF00;">8&nbsp;</li>
                            <li style="background-color: #64FF00;">6&nbsp;</li>
                            <li style="background-color: #00EB00;">4&nbsp;</li>
                            <li style="background-color: #00DC8C;">2&nbsp;</li>
                        </ul>
                        <ul class="scale-lower">
                            <li style="background-color: #00DCFF;">0&nbsp;</li>
                            <li style="background-color: #00AFFF;">-2</li>
                            <li style="background-color: #007DFF;">-4</li>
                            <li style="background-color: #0046F5;">-6</li>
                            <li style="background-color: #0014B4;">-8</li>
                            <li style="background-color: #6E0A6E;">-10</li>
                            <li style="background-color: #8C008C;">-12</li>
                            <li style="background-color: #B400B4;">-15</li>
                            <li style="background-color: #D200D2;">-20</li>
                            <li style="background-color: #FF00FF;">-25</li>
                            <li style="background-color: #FF78FF;">-30</li>
                            <li style="background-color: #FFAAFF;">-35</li>
                            <li style="background-color: #FFD2FF;">-40</li>
                            <li style="background-color: #EAE9F9;">-45</li>
                        </ul>
                    </div>
                    <div class="scale-fahrenheit">
                        <ul class="scale-upper">
                            <li style="background-color: #FFC8C8;">114.8</li>
                            <li style="background-color: #FFA0A0;">111.2</li>
                            <li style="background-color: #FF82B4;">107.6</li>
                            <li style="background-color: #FF5096;">104</li>
                            <li style="background-color: #FF0064;">100.4</li>
                            <li style="background-color: #D2005A;">96.8</li>
                            <li style="background-color: #A50041;">93.2</li>
                            <li style="background-color: #780000;">89.6</li>
                            <li style="background-color: #A00000;">86</li>
                            <li style="background-color: #C30000;">82.4</li>
                            <li style="background-color: #E60000;">78.8</li>
                            <li style="background-color: #FF5A00;">75.2</li>
                            <li style="background-color: #FF8200;">71.6</li>
                            <li style="background-color: #FFA000;">68</li>
                            <li style="background-color: #FFBE00;">64.4</li>
                            <li style="background-color: #FFD200;">60.8</li>
                            <li style="background-color: #FFE600;">57.2</li>
                            <li style="background-color: #FFFF14;">53.6</li>
                        </ul>
                        <ul class="scale-lower">
                            <li style="background-color: #C8FF00;">50</li>
                            <li style="background-color: #96FF00;">46.4</li>
                            <li style="background-color: #64FF00;">42.8</li>
                            <li style="background-color: #00EB00;">39.2</li>
                            <li style="background-color: #00DC8C;">35.6</li>
                            <li style="background-color: #00DCFF;">32</li>
                            <li style="background-color: #00DCFF;">32</li>
                            <li style="background-color: #00AFFF;">28.4</li>
                            <li style="background-color: #007DFF;">24.8</li>
                            <li style="background-color: #0046F5;">21.2</li>
                            <li style="background-color: #0014B4;">17.6</li>
                            <li style="background-color: #6E0A6E;">14</li>
                            <li style="background-color: #8C008C;">10.4</li>
                            <li style="background-color: #B400B4;">5&nbsp;</li>
                            <li style="background-color: #D200D2;">-4</li>
                            <li style="background-color: #FF00FF;">-13</li>
                            <li style="background-color: #FF78FF;">-22</li>
                            <li style="background-color: #FFAAFF;">-31</li>
                            <li style="background-color: #FFD2FF;">-40</li>
                            <li style="background-color: #EAE9F9;">-49</li>
                        </ul>
                    </div>
                    <div class="unit-buttons">
                        <button type="button" id="celsius-switch">°C</button>
                        <button type="button" id="fahrenheit-switch">°F</button>
                    </div>
                </div>
                
                
                <div id="province-select">
                    <h6>Province:</h6>
                    <select name="philippine-regions">
                        <option>Choose one...</option>

                        <optgroup label="Major Areas">
                            <option data-region-id="Philippines">All Philippines</option>
                            <option data-region-id="Luzon">Luzon</option>
                            <option data-region-id="VisMin">Visayas/Mindanao</option>
                            <option data-region-id="Palawan">Palawan/Sulu Sea</option>
                        </optgroup>

                        <optgroup label="Luzon">
                            <option data-region-id="NCR">NCR</option>
                            <option data-region-id="CAR">CAR</option>
                            <option data-region-id="I">Ilocos</option>
                            <option data-region-id="II">Cagayan Valley</option>
                            <option data-region-id="III">Central Luzon</option>
                            <option data-region-id="IVa">CALABARZON</option>
                            <option data-region-id="IVb">MIMAROPA</option>
                            <option data-region-id="V">Bicol</option>
                        </optgroup>

                        <optgroup label="Visayas">
                            <option data-region-id="VI">Western Visayas</option>
                            <option data-region-id="VII">Central Visayas</option>
                            <option data-region-id="VIII">Eastern Visayas</option>
                        </optgroup>

                        <optgroup label="Mindanao">
                            <option data-region-id="IX">Zamboanga Peninsula</option>
                            <option data-region-id="X">Northern Mindanao</option>
                            <option data-region-id="XI">Davao</option>
                            <option data-region-id="XII">SOCCSKSARGEN</option>
                            <option data-region-id="XIII">CARAGA</option>
                            <option data-region-id="ARMM">ARMM</option>
                        </optgroup>
                    </select>
                </div> <!--END PROVINCE SELECT-->
            </div> <!--END LEGEND-->
        </div>
        <div id="info" class="shadow">
            <div id="current-readings-panel">
                <h2 class="current readings-location">&nbsp;</h2>
                <!--                <a href="#" >change station</a>-->
                <p>Current Readings:</p>
            </div>
            <div id="current-readings-box">

                <div class="readings shadow">
                    <p>last updated: <span class="last-update">--:--</span></p>
                    <span class="current temperature"><span>&nbsp;</span>&deg;C</span>
                    <span class="symbol"></span>
                    <table>
                        <tbody>
                            <tr>
                                <td>Wind</td>
                                <td class="current wind"><span>&nbsp;</span>km/h</td>
                            </tr>
                            <tr>
                                <td>Rain</td>
                                <td class="current precipitation"><span>&nbsp;</span>mm</td>
                            </tr>
                            <tr>
                                <td>Humidity</td>
                                <td class="current humidity"><span>&nbsp;</span>%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="no-readings">
                    <p>Sorry, there's no readings available for this station right now. Please try another.</p>
                </div>
                <div class="day-forecast">
                    <div class="detail-page-link">
                        <h2 style="height: 21px; text-align: center; width: 195px;">
                            <a href="<?= $this->webroot ?>view" style="background: url('<?= $this->webroot ?>theme/weatherph/img/arrow.png') no-repeat left center; padding-left: 40px; margin: 0 auto;">
                                More Details
                            </a>
                        </h2>
                    </div>
                    <ul>
                        <li class="forecast-highlight">
                            <h6 class="0-hour time">--:--</h6>
                            <div class="0-hour readings">
                                <span class="temperature"><span>&nbsp;</span>&deg;C</span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="3-hour wind"><span>&nbsp;</span>km/h</td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span>mm</td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span>%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li>
                            <h6 class="1-hour time">--:--</h6>
                            <div class="1-hour readings">
                                <span class="temperature"><span>&nbsp;</span>&deg;C</span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="wind"><span>&nbsp;</span>km/h</td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span>mm</td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span>%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li class="forecast-highlight">
                            <h6 class="2-hour time">--:--</h6>
                            <div class="2-hour readings">
                                <span class="temperature"><span>&nbsp;</span>&deg;C</span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="wind"><span>&nbsp;</span>km/h</td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span>mm</td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span>%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li>
                            <h6 class="3-hour time">--:--</h6>
                            <div class="3-hour readings">
                                <span class="temperature"><span>&nbsp;</span>&deg;C</span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="wind"><span>&nbsp;</span>km/h</td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span>mm</td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span>%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li class="forecast-highlight">
                            <h6 class="4-hour time">--:--</h6>
                            <div class="4-hour readings">
                                <span class="temperature"><span>&nbsp;</span>&deg;C</span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="wind"><span>&nbsp;</span>km/h</td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span>mm</td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span>%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li>
                            <h6 class="5-hour time">--:--</h6>
                            <div class="5-hour readings">
                                <span class="temperature"><span>&nbsp;</span>&deg;C</span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="wind"><span>&nbsp;</span>km/h</td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span>mm</td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span>%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li class="forecast-highlight">
                            <h6 class="6-hour time">--:--</h6>
                            <div class="6-hour readings">
                                <span class="temperature"><span>&nbsp;</span>&deg;C</span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="wind"><span>&nbsp;</span>km/h</td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span>mm</td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span>%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="no-forecast">
                    <p>Sorry, there's no forecast available for this station right now.</p>
                </div>
            </div> <!--INFO-->
    </section> <!--MAIN CONTENT-->
    <section class="secondary">
        <div class="blog">
            <h4>Blog</h4>
            <div class="posts">
                <?php foreach ($blogEntries as $blog) { ?>
                    <div class="blog-preview">
                        <?php $createdTime = strtotime($blog['Node']['created']); ?>



                        <div class="ribbon-wrapper">
                            <div class="ribbon-front">
                            <div class="ribbon-top-bar"></div>
                                <div class="date">
                                    <div class="month"><?= date('M', $createdTime) ?></div>
                                    <div class="day"><?= date('d', $createdTime) ?></div>
                                    <div class="year"><?= date('Y', $createdTime) ?></div>
                                </div>
                            </div>
                            <div class="ribbon-edge-topleft"></div>
                            <div class="ribbon-edge-topright"></div>
                            <div class="ribbon-edge-bottomleft"></div>
                            <div class="ribbon-edge-bottomright"></div>

                        </div>
                        
                        <h4><?= $html->link($blog['Node']['title'], $blog['Node']['url'], array('class' => 'link')) ?></h4>  
                        <div class="blog-posts">
                        <p><?= $text->excerpt(strip_tags($blog['Node']['body']), 'method', 200, '...' . $html->link('Read More...', $blog['Node']['url'])) ?><?= '<hr>'; ?></p>
                        </div>

                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="news">
            <h4>Breaking News</h4>
            <ul>
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail.png"/><p>Breaking News</p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail2.png"/><p>Weather TV</p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail3.png"/><p>Mike Padua: Typhoons, Explained</p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail.png"/><p>Meteomedia Weather Shop</p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail2.png"/><p>Webcams</p></li>
            </ul>
        </div>

    </section> <!--SECONDARY-->
</div> <!--CONTENT-->
