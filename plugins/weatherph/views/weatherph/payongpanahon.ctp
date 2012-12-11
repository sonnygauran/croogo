<div class="content">
    <section class="main">
        <div class="page">
            <?php $this->set('title_for_layout', 'Payong Panahon'); ?>
            <h2>Payong Panahon</h2>
            <hr/>
            <?php foreach ($archives as $blog) { ?>
                <?php $createdTime = strtotime($blog['Node']['created']); ?>
                <div class="blog-excerpt">
                    <h2><?= $html->link($blog['Node']['title'], $blog['Node']['url'], array('class' => 'link')) ?></h2>
                    <p><?= $text->excerpt(strip_tags($blog['Node']['body']), 'method', 200, '...' . $html->link('Read More', $blog['Node']['url'])) ?></p>
                    <p class="post-meta">
                        Posted on <?= date('M', $createdTime) ?> <?= date('d', $createdTime) ?>, <?= date('Y', $createdTime) ?>
                    </p>
                </div>
                <hr>
            <?php } ?>
        </div>
    </section>
</div>
