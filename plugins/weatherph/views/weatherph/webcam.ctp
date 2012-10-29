<?php
$base =  Router::url('/', true);
$base = substr($base, 0, -1);
?>
<div class="content">
    <section class="main">
        <div class="page">
           <?php  $this->set('title_for_layout', 'Webcams'); ?>
        <h2>Webcams</h2>
        <hr/>
        <p>Makati City, facing Northeast</p>
        <video width="720" height="405" poster="<?= Configure::read('Server.node')?>as_ph_manilashang.jpg" controls>
            <source src="<?= Configure::read('Server.node')?>as_ph_manilashang.m4v" type='video/x-m4v;'/>
            <source src="<?= Configure::read('Server.node')?>as_ph_manilashang.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
            <source src="<?= Configure::read('Server.node')?>as_ph_manilashang.webm" type='video/webm; codecs="vp8, vorbis"' />
            Your browser does not support the video tag.
        </video>
        <hr/>
        <p>Amanpulo/Pamalican Island facing Northwest</p>
        <video width="720" height="405" poster="<?= Configure::read('Server.node')?>as_ph_amanpulo.jpg" controls>
            <source src="<?= Configure::read('Server.node')?>as_ph_amanpulo.m4v" type='video/x-m4v;'/>
            <source src="<?= Configure::read('Server.node')?>as_ph_amanpulo.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
            <source src="<?= Configure::read('Server.node')?>as_ph_amanpulo.webm" type='video/webm; codecs="vp8, vorbis"' />
            Your browser does not support the video tag.
        </video>
        </div>
    </section><!--MAIN CONTENT-->
</div><!--CONTENT-->