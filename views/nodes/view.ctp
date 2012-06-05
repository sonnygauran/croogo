<div class="content">
    <div class="main">
        <div class="posts">
            <?php $this->Layout->setNode($node); ?>
            <div id="node-<?php echo $this->Layout->node('id'); ?>" class="node node-type-<?php echo $this->Layout->node('type'); ?>">
                <h2><?php echo $this->Layout->node('title'); ?></h2>
                <hr/>
                <?php
                    echo $this->Layout->nodeInfo();
                    echo $this->Layout->nodeBody();
                ?>
            </div>
        </div>
    </div>
</div>
