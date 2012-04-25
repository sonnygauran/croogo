<script type="text/javascript" src="weatherph/js/weatherph/index.js"></script>
<style type="text/css">
    .loader {
        background: white url(theme/weatherph_compact/img/loader-twirl.gif) no-repeat center center;
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
<div id="content">
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
                <img src ="theme/weatherph_compact/img/timeline.png"/>
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
                    <img src="theme/weatherph_compact/img/legend-blue.png" alt="" />
                    <h6>Meteomedia stations</h6>
                    <img src="theme/weatherph_compact/img/legend-red.png" alt="" />
                    <h6>PAGASA stations</h6>
                </div>-->
<!--                            
                <img src ="theme/weatherph_compact/img/legend.png"/>
-->
            </div> <!--END LEGEND-->
        </div>
        <div id="info">
            <div id="current-readings-panel">
                <h2 class="current readings-location">&nbsp;</h2>
                <a href="#">change station</a>
                <h4>Current Readings:</h4>
                
            </div>
            <div class="readings">
                <p style="margin: 5px 0;">last updated: <span class="last-update">--:--</span></p>
                <h3 class="current temperature"><span>&nbsp;</span>&deg;C</h3>
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
            <div class="detail-page-link">
                <h2>More Details<h2>
                <?php echo $this->Html->image('arrow.png'); ?>
            </div>
            <div class="day-forecast">
                <ul>
                    <li>
                        <h6 class="0-hour time">--:--</h6>
                        <div class="0-hour readings">
                            <h3 class="temperature"><span>&nbsp;</span>&deg;C</h3>
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
                            <h3 class="temperature"><span>&nbsp;</span>&deg;C</h3>
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
                        <h6 class="2-hour time">--:--</h6>
                        <div class="2-hour readings">
                            <h3 class="temperature"><span>&nbsp;</span>&deg;C</h3>
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
                            <h3 class="temperature"><span>&nbsp;</span>&deg;C</h3>
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
                        <h6 class="4-hour time">--:--</h6>
                        <div class="4-hour readings">
                            <h3 class="temperature"><span>&nbsp;</span>&deg;C</h3>
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
                            <h3 class="temperature"><span>&nbsp;</span>&deg;C</h3>
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
                        <h6 class="6-hour time">--:--</h6>
                        <div class="6-hour readings">
                            <h3 class="temperature"><span>&nbsp;</span>&deg;C</h3>
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
            <h4>What is a typhoon?</h4>
            <p>
                A typhoon is a mature tropical cyclone that develops in the northwestern part of the Pacific Ocean between 180° and 100°E. This region is referred to as the northwest Pacific basin. For organizational purposes, the northern Pacific Ocean is divided into three regions: the eastern (North America to 140°W), central (140°W to 180°), and western (180° to 100°E). Identical phenomena in the eastern north Pacific are called hurricanes, with tropical cyclones moving into the western Pacific re-designated as typhoons. The Regional Specialized Meteorological Center (RSMC) for tropical cyclone forecasts is in Japan, with other tropical cyclone warning centers for the northwest Pacific in Honolulu (the Joint Typhoon Warning Center), the Philippines, and Hong Kong. While the RSMC names each system, the main name list itself is coordinated amongst 18 countries, including the United States, who have territories threatened by typhoons each year. The Philippines uses their own naming list for systems which approach the country.
            </p>

<!--                        <h4>How do they name typhoons?</h4>
            <p>
                The list of names consists of entries from 17 East Asian nations and the United States who have territories directly affected by typhoons. The submitted names are arranged into five lists; and each list is cycled with each year. Unlike tropical cyclones in other parts of the world, typhoons are not named after people. Instead, they generally refer to animals, flowers, astrological signs, and a few personal names. However, PAGASA retains its own naming list, which does consist of human names. Therefore, a typhoon can possibly have two names. Storms that cross the date line from the central Pacific retain their original name, but the designation of hurricane becomes typhoon. In Japan, typhoons are also given a numerical designation according to the sequence of their occurrence in the calendar year.
            </p>-->
        </div>

        <div class="news">
            <h4>Breaking News</h4>
            <ul>
                <li><img src="theme/weatherph_compact/img/thumbnail.png"/><p>Breaking News</p></li>
                <li><img src="theme/weatherph_compact/img/thumbnail2.png"/><p>Weather TV</p></li>
                <li><img src="theme/weatherph_compact/img/thumbnail3.png"/><p>Mike Padua: Typhoons, Explained</p></li>
                <li><img src="theme/weatherph_compact/img/thumbnail.png"/><p>Meteomedia Weather Shop</p></li>
                <li><img src="theme/weatherph_compact/img/thumbnail2.png"/><p>Webcams</p></li>
            </ul>
        </div>

    </section> <!--SECONDARY-->
</div> <!--CONTENT-->
