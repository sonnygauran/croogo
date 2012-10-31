<div class="content">
    <section class="main">
        <div class="page">
            <?php $this->set('title_for_layout', 'Weather TV'); ?>
            <h2>Weather TV</h2>
            <hr/>
            <?php
                echo "<h2><a href='/weathertv/{$latest_video['Node']['slug']}'>{$latest_video['Node']['title']}</a></h2>";
                echo $latest_video['Node']['body'];
            ?>
            <br/>
            <hr>
            <?php foreach ($videos as $video) { ?>
                <?php $createdTime = strtotime($video['Node']['created']); ?>
                <div class="video-excerpt box">
                    <a href="/weather/<?= $video['Node']['slug']?>">
                     <?= $video['Node']['excerpt']; ?>
                     <?= $video['Node']['title']; ?>
                    </a>
                </div>
                
            <?php } ?>
        </div>
    </section>
</div>
