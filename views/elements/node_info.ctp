<div class="node-info">
    <?php
    $type = $types_for_layout[$this->Layout->node('type')];
    if ($type['Type']['format_show_date']) {
        $createdTime = strtotime($this->Layout->node('created'));
        ?>
        <div class="ribbon-wrapper blog-page">
            <div class="ribbon-front blog-page">
                <div class="ribbon-top-bar"></div>
                <div class="post-date">
                    <div class="day"><?= date('d', $createdTime) ?></div>
                    <div class="month"><?= date('M', $createdTime) ?></div>
                    <div class="year"><?= date('Y', $createdTime) ?></div>
                </div>
            </div>
            <div class="ribbon-edge-bottomleft blog-page"></div>
        </div>
        <?php
    }
    ?>
</div>