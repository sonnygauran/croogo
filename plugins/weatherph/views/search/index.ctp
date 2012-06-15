<div class="main">
    <div class="names index">
        <h2><?php __('NimaNames'); ?></h2>
        <ul>
            <?php foreach ($names as $name):?>
                <li><?php echo $name['NimaName']['full_name_ro']; ?>&nbsp;</li>
            <?php endforeach; ?>
        </ul>
        <p>
            <?php
            echo $this->Paginator->counter(array(
                'format' => __('Page %page% of %pages%, showing %current% results out of %count%', true)
            ));
            ?>	</p>

        <div class="paging">
            <?php echo $this->Paginator->prev(__('previous', true), array(), null, array('class' => 'disabled')); ?>
            <?php echo $this->Paginator->numbers(); ?>
            <?php echo $this->Paginator->next(__('next', true), array(), null, array('class' => 'disabled')); ?>
        </div>
    </div>
    <div class="actions">
        <h3><?php __('Actions'); ?></h3>
        <ul>
            <li><?php echo $this->Html->link(__('New NimaName', true), array('action' => 'add')); ?></li>
        </ul>
    </div>
</div>