<div class="content">
    <section class="main">
        <div class="page">
           <?php  $this->set('title_for_layout', 'Webcams'); ?>
        <h2>Webcams</h2>
        <hr/>
        <p>Makati city, facing East</p>
        <video width="720" height="405" poster="<?php echo Configure::read('Data.movies'); ?>as_ph_manilashang.jpg" controls autobuffer>
            <source src="<?php echo Configure::read('Data.movies'); ?>as_ph_manilashang.m4v" type='video/x-m4v;'/>
            <source src="<?php echo Configure::read('Data.movies'); ?>as_ph_manilashang.mp4" type='video/mp4;'/>
            <source src="<?php echo Configure::read('Data.movies'); ?>as_ph_manilashang.webm" type='video/webm;'/>
            Your browser does not support the video tag.
        </video>
        <hr/>
        <p>Amanpulo</p>
        <video width="720" height="405" poster="<?php echo Configure::read('Data.movies'); ?>as_ph_amanpulo.jpg" controls autobuffer>
            <source src="<?php echo Configure::read('Data.movies'); ?>as_ph_amanpulo.m4v" type='video/x-m4v;'/>
            <source src="<?php echo Configure::read('Data.movies'); ?>as_ph_amanpulo.mp4" type='video/mp4;'/>
            <source src="<?php echo Configure::read('Data.movies'); ?>as_ph_amanpulo.webm" type='video/webm;'/>
            Your browser does not support the video tag.
        </video>
        </div>
    </section><!--MAIN CONTENT-->
</div><!--CONTENT-->