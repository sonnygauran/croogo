<script type="text/javascript" src="<?= $this->webroot ?>weatherph/js/weatherph/results.js"></script>
<div class="content">
    <section class="main">
        <div id="map-container">
            <div class="map-viewport">
                <div id="map">
                    <div class="loader">
                    </div>
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
        </div>

        <div class="names index">
            <h2><?php __('Search results'); ?></h2>
            <ul class="search-results">
                <?php foreach ($names as $name): ?>
                    <li href="#" class="<?php echo $name['NimaName']['id']; ?> location" id="<?php echo $name['NimaName']['id']; ?>"><a href ="/dmoForecast/<?php echo $name['NimaName']['id']; ?>" ><?php echo $name['NimaName']['full_name_ro']; ?>&nbsp;</a></li>
                <?php endforeach; ?>
            </ul>
            <br/>
            <div class="paging">
                <?php
                if ($this->Paginator->hasPage(2)) {
                    echo $this->Paginator->counter(array(
                        'format' => __('Page %page% of %pages%, showing %current% results out of %count%', true)
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