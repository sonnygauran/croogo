<div class="content">
    <section class="main">
        <div class="page">
            <h2>Payong Panahon</h2>
            <hr/>
            <?php foreach ($blogLists as $blog) { ?>
                <?php $createdTime = strtotime($blog['Node']['created']); ?>
                <div class="ribbon-wrapper">
                    <div class="ribbon-front">
                        <div class="post-date">
                            <div class="month"><?= date('M', $createdTime) ?></div>
                            <div class="day"><?= date('d', $createdTime) ?></div>
                            <div class="year"><?= date('Y', $createdTime) ?></div>
                        </div>
                    </div>
                    <div class="ribbon-edge-bottomleft"></div>
                </div>

                <div class="blog-excerpt">
                    <h4><?= $html->link($blog['Node']['title'], $blog['Node']['url'], array('class' => 'link')) ?></h4>
                    <p><?= $text->excerpt(strip_tags($blog['Node']['body']), 'method', 200, '...' . $html->link('Read More', $blog['Node']['url'])) ?><?= '<hr>'; ?></p>
                </div>
            <?php } ?>
        </div>
    </section>
</div>
