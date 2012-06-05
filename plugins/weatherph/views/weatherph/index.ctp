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
                <ul class="movies dropdown">
                    <li><a href="#" id="link-map">Weather stations</a></li>
                    <li>
                        <a href="#">Weather movies</a>
                        <ul>
                            <li><a href="#map" id="link-video-wind"   data-name="wind"          data-target="video-wind">Wind</a></li>
                            <li><a href="#map" id="link-video-precip" data-name="precipitation" data-target="video-precip">Precipitation</a></li>
                        </ul>
                    </li>
                </ul>
            </div> <!--LAYER SELECTOR-->
            <div class="video-viewport">

            </div>
            <div class="map-viewport">
                <div id="map">
                    <div class="loader">
                    </div>
                </div>
            </div>
            <div id="legend" class="shadow">

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
<!--
                <div id="station-color">
                    <img src="theme/weatherph/img/legend-blue.png" alt="" />
                    <h6>Meteomedia stations</h6>
                    <img src="theme/weatherph/img/legend-red.png" alt="" />
                    <h6>PAGASA stations</h6>
                </div>
                            
                <img src ="theme/weatherph/img/legend.png"/>
-->
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
                                <td>Precip.</td>
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
                                        <td>Precip.</td>
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
                                        <td>Precip.</td>
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
                                        <td>Precip.</td>
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
                                        <td>Precip.</td>
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
                                        <td>Precip.</td>
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
                                        <td>Precip.</td>
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
                                        <td>Precip.</td>
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
            <?php foreach($featuredBlog as $blog){ ?>
                <h4><?=$html->link($blog['Node']['title'], $blog['Node']['url'], array('class' => 'link'))?></h4>  
                <p><?=$text->excerpt(strip_tags($blog['Node']['body']), 'method', 200, '...' . $html->link('Read More...', $blog['Node']['url']))?></p>
                <h4><?=$html->link($blog['Node']['title'], $blog['Node']['url'], array('class' => 'link'))?></h4>  
                <p><?=$text->excerpt(strip_tags($blog['Node']['body']), 'method', 200, '...' . $html->link('Read More...', $blog['Node']['url']))?></p>
                <h4><?=$html->link($blog['Node']['title'], $blog['Node']['url'], array('class' => 'link'))?></h4>  
                <p><?=$text->excerpt(strip_tags($blog['Node']['body']), 'method', 200, '...' . $html->link('Read More...', $blog['Node']['url']))?></p>
            <?php }?>
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