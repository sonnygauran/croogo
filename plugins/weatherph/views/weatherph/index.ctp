<div class="content">
    <section class="main cf">
        <div id="map-container">
            <div class="layer-selector cf">
                <ul class="movies data-layers dropdown">
                    <li><a href="#" data-target="data-layer" data-name="stations">Weather stations</a></li>
                    <li>
                        <a href="#">Weather movies &#9663;</a>
                        <ul>
                            <li><a href="#map" data-type="movie" data-name="wind" data-target="movie-wind">Wind</a></li>
                            <li><a href="#map" data-type="movie" data-name="precipitation" data-target="movie-precip">Rain</a></li>
                        </ul>
                    </li>
                    <li><a data-target="data-layer" data-name="temperature" href="#map">Temperature</a></li>
                    <li><a data-target="data-layer" data-name="pressure" href="#map">Pressure</a></li>
                </ul>
            </div> <!--LAYER SELECTOR-->
            <div id="video-viewport">

            </div>
            <div class="map-viewport">
                <div class="data-layer" style="width: 554px; height: 554px; float:left" data-bbox="">
                </div>
                <div id="map">
                    <div id="loader">
                    </div>
                    <div class="hovered-station">
                    </div>
                </div>
            </div>
            <div id="legend" class="shadow">
                <div class="scale-temperature">
                    <div class="unit-buttons">
                        <button type="button" id="celsius-switch">C</button>
                        <button type="button" id="fahrenheit-switch">F</button>
                    </div>
                    <div class="scale-celsius">
                        <ul>
                            <li style="background-color: #EAE9F9;">-45</li>
                            <li style="background-color: #FFD2FF;">-40</li>
                            <li style="background-color: #FFAAFF;">-35</li>
                            <li style="background-color: #FF78FF;">-30</li>
                            <li style="background-color: #FF00FF;">-25</li>
                            <li style="background-color: #D200D2;">-20</li>
                            <li style="background-color: #B400B4;">-15</li>
                            <li style="background-color: #8C008C;">-12</li>
                            <li style="background-color: #6E0A6E;">-10</li>
                            <li style="background-color: #0014B4;">-8</li>
                            <li style="background-color: #0046F5;">-6</li>
                            <li style="background-color: #007DFF;">-4</li>
                            <li style="background-color: #00AFFF;">-2</li>
                            <li style="background-color: #00DCFF;">0&nbsp;</li>
                            <li style="background-color: #00DC8C;">2&nbsp;</li>
                            <li style="background-color: #00EB00;">4&nbsp;</li>
                            <li style="background-color: #64FF00;">6&nbsp;</li>
                            <li style="background-color: #96FF00;">8&nbsp;</li>
                        </ul>
                        <ul>
                            <li style="background-color: #C8FF00;">10</li>
                            <li style="background-color: #FFFF14;">12</li>
                            <li style="background-color: #FFE600;">14</li>
                            <li style="background-color: #FFD200;">16</li>
                            <li style="background-color: #FFBE00;">18</li>
                            <li style="background-color: #FFA000;">20</li>
                            <li style="background-color: #FF8200;">22</li>
                            <li style="background-color: #FF5A00;">24</li>
                            <li style="background-color: #E60000;">26</li>
                            <li style="background-color: #C30000;">28</li>
                            <li style="background-color: #A00000;">30</li>
                            <li style="background-color: #780000;">32</li>
                            <li style="background-color: #A50041;">34</li>
                            <li style="background-color: #D2005A;">36</li>
                            <li style="background-color: #FF0064;">38</li>
                            <li style="background-color: #FF5096;">40</li>
                            <li style="background-color: #FF82B4;">42</li>
                            <li style="background-color: #FFA0A0;">44</li>
                            <li style="background-color: #FFC8C8;">46</li>
                        </ul>
                    </div>
                    <div class="scale-fahrenheit">
                        <ul>
                            <li style="background-color: #EAE9F9;">-49</li>
                            <li style="background-color: #FFD2FF;">-40</li>
                            <li style="background-color: #FFAAFF;">-31</li>
                            <li style="background-color: #FF78FF;">-22</li>
                            <li style="background-color: #FF00FF;">-13</li>
                            <li style="background-color: #D200D2;">-4</li>
                            <li style="background-color: #B400B4;">5&nbsp;</li>
                            <li style="background-color: #8C008C;">10</li>
                            <li style="background-color: #6E0A6E;">14</li>
                            <li style="background-color: #0014B4;">18</li>
                            <li style="background-color: #0046F5;">21</li>
                            <li style="background-color: #007DFF;">25</li>
                            <li style="background-color: #00AFFF;">28</li>
                            <li style="background-color: #00DCFF;">32</li>
                            <li style="background-color: #00DC8C;">36</li>
                            <li style="background-color: #00EB00;">39</li>
                            <li style="background-color: #64FF00;">43</li>
                            <li style="background-color: #96FF00;">46</li>
                            <li style="background-color: #C8FF00;">50</li>
                        </ul>
                        <ul>
                            <li style="background-color: #FFFF14;">54</li>
                            <li style="background-color: #FFE600;">57</li>
                            <li style="background-color: #FFD200;">61</li>
                            <li style="background-color: #FFBE00;">64</li>
                            <li style="background-color: #FFA000;">68</li>
                            <li style="background-color: #FF8200;">72</li>
                            <li style="background-color: #FF5A00;">75</li>
                            <li style="background-color: #E60000;">79</li>
                            <li style="background-color: #C30000;">82</li>
                            <li style="background-color: #A00000;">86</li>
                            <li style="background-color: #780000;">90</li>
                            <li style="background-color: #A50041;">93</li>
                            <li style="background-color: #D2005A;">97</li>
                            <li style="background-color: #FF0064;">100</li>
                            <li style="background-color: #FF5096;">104</li>
                            <li style="background-color: #FF82B4;">108</li>
                            <li style="background-color: #FFA0A0;">111</li>
                            <li style="background-color: #FFC8C8;">115</li>
                        </ul>
                    </div>
                </div>
                <div class="scale-pressure">
                    <ul>
                        <li style="background-color: #5A2623;">1045</li>
                        <li style="background-color: #823C35;">1040</li>
                        <li style="background-color: #9F4141;">1035</li>
                        <li style="background-color: #B74D4D;">1030</li>
                        <li style="background-color: #D06464;">1025</li>
                        <li style="background-color: #F09292;">1020</li>
                        <li style="background-color: #FFC0C0;">1015</li>
                        <li style="background-color: #DCDCDC;">1010</li>
                        <li style="background-color: #DAEDEA;">1005</li>
                        <li style="background-color: #BADCD6;">1000</li>
                        <li style="background-color: #95C0B8;">995</li>
                        <li style="background-color: #74A49C;">990</li>
                        <li style="background-color: #588980;">985</li>
                        <li style="background-color: #416E66;">980</li>
                        <li style="background-color: #2A5A52;">975</li>
                        <li style="background-color: #15463C;">970</li>
                        <li style="background-color: #003228;">965</li>
                        <li style="background-color: #001E14;">960</li>
                    </ul>
                </div>

                <div class="province-select">
                    <span>Province:</span>
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
                <div class="station-legend">
                    <div style="background-color: #4762ff;"></div>
                    <span>Meteomedia stations</span>
                    <div style="background-color: #fd7567;"></div>
                    <span>PAGASA stations</span>
                </div>
            </div> <!--END LEGEND-->
        </div>
        <div id="info" class="shadow">
            <div id="current-readings-panel">
                <h2 id="readings-location">&nbsp;</h2>
                <p>Current Readings:</p>
            </div>
            <div id="current-readings-box">
                <div class="readings shadow">
                    <p>last updated: <span id="last-update">--:--</span></p>
                    <span class="current temperature"><span>&nbsp;</span></span>
                    <span class="symbol"></span>
                    <table>
                        <tbody>
                            <tr>
                                <td>Wind</td>
                                <td class="current wind"><span>&nbsp;</span></td>
                            </tr>
                            <tr>
                                <td>Rain<span class="precipitation_hr_range"></span></td>
                                <td class="current precipitation"><span>&nbsp;</span></td>
                            </tr>
                            <tr>
                                <td>Humidity</td>
                                <td class="current humidity"><span>&nbsp;</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="no-readings">
                    <p>Sorry, there's no readings available for this station right now. Please try another.</p>
                </div>
                <div class="day-forecast">
                    <div class="detail-page-link">
                        <h3 style="height: 21px; text-align: center; width: 195px;">
                            <a href="<?= $this->webroot ?>view" style="background: url('<?= $this->webroot ?>theme/weatherph/img/arrow.png') no-repeat left center; padding-left: 40px; margin: 0 auto;">
                                More Details
                            </a>
                        </h3>
                    </div>
                    <ul>
                        <li class="forecast-highlight">
                            <h6 class="0-hour time">--:--</h6>
                            <div class="0-hour readings">
                                <span class="temperature"><span>&nbsp;</span></span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="3-hour wind"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li>
                            <h6 class="1-hour time">--:--</h6>
                            <div class="1-hour readings">
                                <span class="temperature"><span>&nbsp;</span></span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="wind"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li class="forecast-highlight">
                            <h6 class="2-hour time">--:--</h6>
                            <div class="2-hour readings">
                                <span class="temperature"><span>&nbsp;</span></span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="wind"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li>
                            <h6 class="3-hour time">--:--</h6>
                            <div class="3-hour readings">
                                <span class="temperature"><span>&nbsp;</span></span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="wind"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li class="forecast-highlight">
                            <h6 class="4-hour time">--:--</h6>
                            <div class="4-hour readings">
                                <span class="temperature"><span>&nbsp;</span></span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="wind"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li>
                            <h6 class="5-hour time">--:--</h6>
                            <div class="5-hour readings">
                                <span class="temperature"><span>&nbsp;</span></span>
                                <span class="symbol"></span>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="wind"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Rain</td>
                                            <td class="precipitation"><span>&nbsp;</span></td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="humidity"><span>&nbsp;</span></td>
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
            <div class="page">
                <?php foreach ($blogEntries as $blog) { ?>
                        <?php $createdTime = strtotime($blog['Node']['created']); ?>
                        <div class="ribbon-wrapper">
                            <div class="ribbon-front">
                                <div class="post-date">
                                    <div class="month"><?= date('M', $createdTime) ?></div>
                                    <div class="day"><?= date('d', $createdTime) ?></div>
                                    <div class="year"><?= date('Y', $createdTime) ?></div>
                                </div>
                            </div>
                            <div class="ribbon-edge-bottomleft"></div>
                        </div>

                        <h4><?= $html->link($blog['Node']['title'], $blog['Node']['url'], array('class' => 'link')) ?></h4>
                        <div class="blog-excerpt">
                        <p><?= $text->excerpt(strip_tags($blog['Node']['body']), 'method', 200, '...' . $html->link('Read More', $blog['Node']['url'])) ?><?= '<hr>'; ?></p>
                        </div>
                <?php } ?>
            </div>
        </div>

        <h4>Learn More</h4>
        <div class="news">
            <ul>
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail.png"/><p>Mata ng Bagyo<br><i style="font-size: 10px;">(Eye of the Storm)</i></p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail.png"/><p><a href="<?= $this->webroot ?>weathertv">Weather TV</a></p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail.png"/><p><a href="<?= $this->webroot ?>typhoon/preparedness">Typhoon Preparedness</a></p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail.png"/><p><a href="<?= $this->webroot ?>typhoon/climatology">Typhoon Climatology</a></p></li>
<!--                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail3.png"/><p>Payong Panahon</p></li>-->
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail.png"/><p><a href="<?= $this->webroot ?>dictionaries/english">Dictionary: English</a></p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail.png"/><p><a href="<?= $this->webroot ?>dictionaries/filipino">Dictionary: Filipino</a></p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph/img/thumbnail.png"/><p><a href="<?= $this->webroot ?>webcam">Webcams</a></p></li>
            </ul>
        </div>

    </section> <!--SECONDARY-->
</div> <!--CONTENT-->
<?php
/**
 * index.js requires the following variable:
 *      - resource - contains an array of (data-layer => (temperature, pressure)) for retreiving the image key.
 */
?>
<?php
    $movie_location = Configure::read('Data.movies');
?>
<script type="text/javascript">
    window["DATA_LAYER"] = null;
    window["DATA_LAYERS"] = <?= json_encode($resources['data-layers']); ?>;

    var windContent = '<?= addslashes(str_replace("\n", "\\", (<<<ECHO
        <video id="movie-wind" width="554" height="554" controls="controls">
        <source src="{$movie_location}Philippines_All_stfi.mp4" type='video/mp4; codecs="avc1.42E01E"'/>
        <source src="{$movie_location}Philippines_All_stfi.webm" type='video/webm; codecs="vp8"'/>
        Your browser does not support the video tag.
        </video>
ECHO
)));
        ?>';
            var precipContent = '<?= addslashes(str_replace("\n", "\\", (<<<ECHO
        <video id="movie-precipitation" width="554" height="554" controls="controls">
        <source src="{$movie_location}Philippines_All_niwofi.mp4" type='video/mp4; codecs="avc1.42E01E"'/>
        <source src="{$movie_location}Philippines_All_niwofi.webm" type='video/webm; codecs="vp8"'/>
        Your browser does not support the video tag.
        </video>    
ECHO
))); ?>';

    window['MOVIE_CONTENT'] = {
        wind         : windContent,
        precipitation: precipContent
    };
</script>
