<script type="text/javascript" src="weatherph/js/weatherph/index.js"></script>
<style type="text/css">
    .loader {
        background: white url(<?= $this->webroot ?>theme/weatherph/img/loader-twirl.gif) no-repeat center center;
        width: 100%; height: 100%; display: block; visibility: none;
    }
    .loader-img {
        margin-top: 275px;
        margin-left: 275px;
    }
</style>
<script type="text/javascript">
    $(function(){
        $('.loader').css('opacity', 0.8);
    });
</script>
<div class="content">
    <section class="main">
        <div id="map-container">
            <div class="layerSelector">
                <ul>
                    <li><a href="#">Weather Stations</a></li>
                    <li><a href="#">Temperature</a></li>
                </ul>
            </div> <!--LAYER SELECTOR-->
            <div id="map">
                <div class="loader" style="">

                </div>
            </div>

            <div id="legend">
<!--                            
                <img src ="theme/weatherph/img/timeline.png"/>
-->
                <div id="province-select">
                    <h6>Province:</h6>
                    <select name="philippine-regions">
                        <option>Choose one...</option>
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
                <!--<div id="station-color">
                    <img src="theme/weatherph/img/legend-blue.png" alt="" />
                    <h6>Meteomedia stations</h6>
                    <img src="theme/weatherph/img/legend-red.png" alt="" />
                    <h6>PAGASA stations</h6>
                </div>-->
<!--                            
                <img src ="theme/weatherph/img/legend.png"/>
-->
            </div> <!--END LEGEND-->
        </div>
        <div id="info">
            <div id="current-readings-panel">
                <h2 class="current readings-location">&nbsp;</h2>
                <a href="#" style="display: none;">change station</a>
                <h4 style="display: none;">Current Readings:</h4>
            </div>
            <div class="readings" style="display: none;">
                <p style="margin: 5px 0;">last updated: <span class="last-update">--:--</span></p>
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

            <div class="detail-page-link" style="text-align: center;">
                <h2 style="height: 21px; text-align: center; width: 195px;">
                    <a href="<?= $this->webroot ?>view" style="background: url(<?= $this->webroot ?>theme/weatherph/img/arrow.png) no-repeat left center; padding-left: 40px; margin: 0 auto;">
                        More Details
                    </a>
                </h2>
            </div>
            <div class="day-forecast">
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
        </div> <!--INFO-->

    </section> <!--MAIN CONTENT-->

    <section class="secondary">
        <div class="posts">
            <h4>Intro to Typhoon Climatology</h4>
            <p>Tropical Cyclones have been a part of Filipino culture since time immemorial, since the Philippines is located within the tropics, surrounded by large sea and ocean basins namely the Western Pacific Ocean, The Philippine and South China Seas. These bodies of water are breeding grounds of tropical cyclones.</p>
            <p>An average of 20 tropical cyclones enter the Philippine Area of Responsibility (PAR), where 10 cross the country (1948-2004 mean avg ‚Äì based on PAGASA Statistics) ‚Äì bringing destruction to properties and loss of lives.</p>
        </div>
<!--
        <div class="news">
            <h4>Breaking News</h4>
            <ul>
                <li><img src="<?= $this->webroot ?>theme/weatherph_compact/img/thumbnail.png"/><p>Breaking News</p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph_compact/img/thumbnail2.png"/><p>Weather TV</p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph_compact/img/thumbnail3.png"/><p>Mike Padua: Typhoons, Explained</p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph_compact/img/thumbnail.png"/><p>Meteomedia Weather Shop</p></li>
                <li><img src="<?= $this->webroot ?>theme/weatherph_compact/img/thumbnail2.png"/><p>Webcams</p></li>
            </ul>
        </div>-->

    </section> <!--SECONDARY-->
</div> <!--CONTENT-->
