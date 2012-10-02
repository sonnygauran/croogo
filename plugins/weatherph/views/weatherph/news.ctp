<div class="content">
    <section class="main">
        <div class="page">
            <?php $this->set('title_for_layout', 'News'); ?>
            <h2>News</h2>
            <hr/>
            <?php foreach ($blogLists as $blog) { ?>
                <?php $createdTime = strtotime($blog['Node']['created']); ?>
                <div class="ribbon-wrapper">
                    <div class="ribbon-front subblog">
                        <div class="post-date">
                            <div class="month"><?= date('M', $createdTime) ?></div>
                            <div class="day"><?= date('d', $createdTime) ?></div>
                            <div class="year"><?= date('Y', $createdTime) ?></div>
                        </div>
                    </div>
                    <div class="ribbon-edge-bottomleft subblog"></div>
                </div>

                <div class="blog-excerpt">
                    <h4><?= $html->link($blog['Node']['title'], $blog['Node']['url'], array('class' => 'link')) ?></h4>
                    <?php if ($blog['Node']['type'] == 'news'){?>
                        <h6><a class="post-category" href="<?= $this->webroot ?>news/payong-panahon">Payong Panahon</a></h6>
                    <?php }elseif($blog['Node']['type'] == 'announcements'){?>
                        <h6><a class="post-category" href="<?= $this->webroot ?>news/mata-ng-bagyo">Mata ng Bagyo</a></h6>
                    <?php } ?>
                    <p><?= $blog['Node']['excerpt']; ?></p>
                </div>
                <hr>
            <?php } ?>
        </div>
    </section>
</div>
