<div class="content">
    <section class="main">
        <div class="page">
            <?php $this->set('title_for_layout', 'News'); ?>
            <h2>News</h2>
            <hr/>
            <?php foreach ($blogLists as $blog) { ?>
                <?php $createdTime = strtotime($blog['Node']['created']); ?>
                <div class="blog-excerpt">
                    <h2><?= $html->link($blog['Node']['title'], $blog['Node']['url'], array('class' => 'link')) ?></h2>
                    <p><?= $text->excerpt(strip_tags($blog['Node']['body']), 'method', 200, '...' . $html->link('Read More', $blog['Node']['url'])) ?></p>
                    <p class="post-meta">
                        Posted on <?= date('M', $createdTime) ?> <?= date('d', $createdTime) ?>, <?= date('Y', $createdTime) ?> under
                        <?php if ($blog['Node']['type'] == 'news') { ?>
                            <a class="post-category" href="<?= $this->webroot ?>news/payong-panahon">Payong Panahon</a>
                        <?php } elseif ($blog['Node']['type'] == 'announcements') { ?>
                            <a class="post-category" href="<?= $this->webroot ?>news/mata-ng-bagyo">Mata ng Bagyo</a>
                        <?php } ?>
                    </p>
                </div>
                <hr>
            <?php } ?>
            <div class="paging"><?php echo $paginator->numbers(); ?></div>
        </div>
    </section>
</div>
