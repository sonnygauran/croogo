<div class="content">
    <section class="main">
        <div class="page">
            <?php $this->set('title_for_layout', 'Weather TV'); ?>
            <h2>Weather TV</h2>
            <hr/>
            <?php
                echo "<h2>{$latest_video['Node']['title']}</h2>";
                echo $latest_video['Node']['body'];
            ?>
            <?php foreach ($videos as $video) { ?>
                <?php $createdTime = strtotime($video['Node']['created']); ?>
                <div class="video-excerpt">
                    <?= $video['Node']['title']; ?>
                    <?= $video['Node']['excerpt']; ?>
                </div>
                <hr>
            <?php } ?>
        </div>
    </section>
</div>
