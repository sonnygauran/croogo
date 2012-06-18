<script type="text/javascript" src="<?= $this->webroot ?>weatherph/js/weatherph/results.js"></script>
<style>
    .marker {   
    width: 8px;
    height: 8px;
    border: 2px solid black;
/*    background: url(../img/marker.png);*/
    position: relative;
    left: -4px;
    top: -4px;
    }
    
    .location{
        padding: 10px 10px;
        border: 1px solid black;
        background-color: #343434;
        display:block;
    }
</style>
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
    <!--
                    <div id="station-color">
                        <img src="theme/weatherph/img/legend-blue.png" alt="" />
                        <h6>Meteomedia stations</h6>
                        <img src="theme/weatherph/img/legend-red.png" alt="" />
                        <h6>PAGASA stations</h6>
                    </div>

                    <img src ="theme/weatherph/img/legend.png"/>
    -->
</div>

<div class="main">
    <div class="names index">
        <h2><?php __('NimaNames'); ?></h2>
        <ul>
            <?php foreach ($names as $name): ?>
                <li><a href ="#" class="location" id="<?php echo $name['NimaName']['id']; ?>"><?php echo $name['NimaName']['full_name_ro']; ?>&nbsp;</a></li>
            <?php endforeach; ?>
        </ul>
        <br/>
        <p>
            <?php
            echo $this->Paginator->counter(array(
                'format' => __('Page %page% of %pages%, showing %current% results out of %count%', true)
            ));
            ?>
        </p>
        <br/>
        <div class="paging">
            <?php
            echo $this->Paginator->prev(__('previous', true), array(), null, array('class' => 'disabled'));
            ?>
            &nbsp;
            <?php
            echo $this->Paginator->numbers();
            ?>
            &nbsp;
            <?php
            echo $this->Paginator->next(__('next', true), array(), null, array('class' => 'disabled'));
            ?>
        </div>
    </div>
