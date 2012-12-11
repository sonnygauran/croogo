<div class="content">
    <section class="main">
        <div class="page">
            <?php $this->set('title_for_layout', 'Archive');?>
            <h2>Archives</h2>
            <hr/>
            <?php foreach ($archives as $blog) { ?>
                <?php $createdTime = strtotime($blog['Node']['created']); ?>
                <div class="blog-excerpt">
                    <h2><?= $html->link($blog['Node']['title'], $blog['Node']['url'], array('class' => 'link')) ?></h2>
                    <p><?php if ($blog['Node']['type'] == 'weathertv') { ?>
                            <?= $blog['Node']['excerpt'] ?>
                        <?php } elseif ($blog['Node']['type'] == 'news' || 'announcements') { ?>
                            <?= $text->excerpt(strip_tags($blog['Node']['body']), 'method', 200, '...' . $html->link('Read More', $blog['Node']['url'])) ?></p>
                    <?php } ?>
                    <p class="post-meta">
                        Posted on <?= date('M', $createdTime) ?> <?= date('d', $createdTime) ?>, <?= date('Y', $createdTime) ?> under
                        <?php if ($blog['Node']['type'] == 'news') { ?>
                            <a class="post-category" href="<?= $this->webroot ?>news/payong-panahon">Payong Panahon</a>
                        <?php } elseif ($blog['Node']['type'] == 'announcements') { ?>
                            <a class="post-category" href="<?= $this->webroot ?>news/mata-ng-bagyo">Mata ng Bagyo</a>
                        <?php } elseif ($blog['Node']['type'] == 'weathertv') { ?>
                            <a class="post-category" href="<?= $this->webroot ?>weathertv">Weather TV</a>
                        <?php } ?>
                    </p>
                </div>
                <hr>
            <?php } ?>
            <div class="paging"><?php echo $paginator->numbers(); ?></div>
        </div>
    </section>
</div>