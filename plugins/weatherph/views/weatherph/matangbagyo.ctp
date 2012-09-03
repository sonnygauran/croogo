<div class="content">
    <section class="main">
        <div class="page">
            <?php $this->set('title_for_layout', 'Mata ng Bagyo'); ?>
            <h2>Mata Ng Bagyo</h2>
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
                    <p><?= $blog['Node']['body']; ?></p>
                </div>
            <?php } ?>
        </div>
    </section>
</div>
