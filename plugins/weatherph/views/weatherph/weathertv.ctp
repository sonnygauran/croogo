<div class="content">
    <section class="main">
        <div class="page">
            <?php  $this->set('title_for_layout', 'WeatherTV'); ?>
        <h2>Weather TV</h2>
        <hr/>
<!--        <iframe width="720" height="405" src="http://www.youtube.com/embed/o4m5ijjas7Q" frameborder="0" allowfullscreen></iframe>-->
        <?php foreach($files as $file){ ?>
            <video controls="" style="display: inline; ">
                <source src="<?= Configure::read('Data.weathertv')?><?= $file['Media']['name'] ?>.m4v" type="video/x-m4v;">
                <source src="<?= Configure::read('Data.weathertv')?><?= $file['Media']['name'] ?>.mp4" type="video/mp4;">
                <source src="<?= Configure::read('Data.weathertv')?><?= $file['Media']['name'] ?>.webm" type="video/webm;">
                Your browser does not support the video tag.
            </video>
        <?php } ?>
        </div>
    </section><!--MAIN CONTENT-->
</div><!--CONTENT-->