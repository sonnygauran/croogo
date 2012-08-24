<? $javascript->link('/weatherph/js/weatherph/results', false); ?>
<div class="content">
    <section class="main">
        <div id="map-container">
            <div class="layer-selector cf">
                <?php
                echo $this->Paginator->counter(array(
                    'format' => __('Showing %count% locations from your search', true)
                ));
                ?>
            </div>
            <div class="map-viewport">
                <div id="map">
                    <div id="loader">
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
            </div>
        </div>

        <div class="names index">
            <div class="search-results shadow">
                <h2><?php __('Search results'); ?></h2>
                <ul>
                    <?php foreach ($names as $name): ?>
                        
                        <?php
                        $cityClass = '';
                        if (strstr(strtolower(trim($name['NimaName']['full_name_ro'])), 'city')) {
                            $cityClass = ' citysearch';
                        }
                        if ($cityClass == '' AND $name['NimaName']['dsg'] == 'adm1') {
                            $cityClass = ' citysearch';
                        }
                        ?>

                        <li class="<?php echo $name['NimaName']['id'] . ' ' . $cityClass; ?>">
                                <!-- <pre><?php echo print_r($name, true);?></pre>-->
                            <a href ="/<?php echo Inflector::slug($name['NimaName']['full_name_ro'], '_') . '-' . $name['NimaName']['id']; ?>" class="<?php echo $name['NimaName']['id']; ?> location <?php echo ($name['FipsCode']['type'] == 2) ? 'city' : 'province'; ?>"><?php echo trim($name['NimaName']['full_name_ro']); ?><br /> <span><?= trim($name['FipsCode']['name']); ?> <?= trim($name['Region']['code']) ?></span></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <br/>
            <div class="paging">
                <?php
                if ($this->Paginator->hasPage(2)) {
                    echo $this->Paginator->counter(array(
                        'format' => __('Page %page% of %pages%', true)
                    ));
                    echo ("<br/>");
                    echo $this->Paginator->prev();
                    echo (" | ");
                }
                ?>
                <?php echo $this->Paginator->numbers(); ?> 
                <?php
                if ($this->Paginator->hasPage(2)) {
                    echo (" | ");
                    echo $this->Paginator->next();
                }
                ?> 
            </div>
        </div>
    </section>
</div>
