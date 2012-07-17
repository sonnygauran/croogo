<div class="blog-payong-panahon">
    <div class="blog-title">
    <h3>Payong Panahon</h3>
    </div>
    <div class="page">
        <?php foreach ($blogLists as $blog) { ?>
            <?php $createdTime = strtotime($blog['Node']['created']); ?>
            <div class="ribbon-wrapper-payong">
                <div class="ribbon-front-payong">
                    <div class="post-date">
                        <div class="month"><?= date('M', $createdTime) ?></div>
                        <div class="day"><?= date('d', $createdTime) ?></div>
                        <div class="year"><?= date('Y', $createdTime) ?></div>
                    </div>
                </div>
                <div class="ribbon-edge-bottomleft-payong"></div>
            </div>

            <h4><?= $html->link($blog['Node']['title'], $blog['Node']['url'], array('class' => 'link')) ?></h4>
            <div class="blog-excerpt">
                <p><?= $text->excerpt(strip_tags($blog['Node']['body']), 'method', 200, '...' . $html->link('Read More', $blog['Node']['url'])) ?><?= '<hr>'; ?></p>
            </div>
        <?php } ?>
    </div>
</div>