<?php
$base = Router::url('/', true);
$base = substr($base, 0, -1);
?>
<div class="content">
    <section class="main">
        <div class="page">
            <?php $this->set('title_for_layout', 'Webcams'); ?>
            <h2>Webcams</h2>
            <hr/>
            <p>Makati City, facing Northeast</p>
            <video width="720" height="405" poster="<?= $video['server'] ?>as_ph_manilashang.jpg" controls  src="<?= $video['server'] ?>as_ph_manilashang.<?= $video['extension'] ?>" type='<?= $video['codec'] ?>'>
                Your browser does not support the video tag.
            </video>
            <hr/>
            <p>Amanpulo/Pamalican Island facing Northwest</p>
            <video width="720" height="405" poster="<?= $video['server'] ?>as_ph_amanpulo.jpg" controls  src="<?= $video['server'] ?>as_ph_amanpulo.<?= $video['extension'] ?>" type='<?= $video['codec'] ?>'>
                Your browser does not support the video tag.
            </video>
        </div>
    </section><!--MAIN CONTENT-->
</div><!--CONTENT-->