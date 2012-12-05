<div class="content">
    <section class="main">
        <div class="page">
            <?php $this->set('title_for_layout', 'Archives'); ?>
            <h2>Archives</h2>
            <hr/>
            <table>
                <tr>
                    <th><?= $this->Paginator->sort('Title', 'title') ?></th>
                    <th><?= $this->Paginator->sort('Excerpt', 'excerpt') ?></th>
                    <th><?= $this->Paginator->sort('Type', 'type') ?></th>
                </tr>
                <?php foreach ($archives as $archive) { ?>
                    <?php $archive_excerpt = substr($archive['Node']['excerpt'], 0, 100) ?>
                    <tr>
                        <td>
                            <?= $html->link($archive['Node']['title'], $archive['Node']['url']) ?>
                        </td>
                        <td></td>
                        <td>
                        <?php
                        
                        switch($archive['Node']['type']){
                            case 'weathertv':
                                echo 'Weather TV';
                            break;
                            case 'announcements':
                                echo 'Payong Panahon';
                            break;
                            case 'news':
                                echo 'Mata ng Bagyo';
                            break;
                        }
                        ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <div class="paging"><?php echo $paginator->numbers(); ?></div>
        </div>
    </section> <!--MAIN CONTENT-->
</div> <!--CONTENT-->