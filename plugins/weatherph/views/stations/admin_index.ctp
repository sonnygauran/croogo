<?php
#$this->Html->script(array('stations'), false);
?>
    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Station', true), array('action' => 'create')); ?></li>
        </ul>
    </div>


    <?php echo $this->Form->create('Station', array('url' => array('controller' => 'stations', 'action' => 'process'))); ?>
    <table cellpadding="0" cellspacing="0">
        <?php
        $tableHeaders = $this->Html->tableHeaders(array(
            '',
            $paginator->sort('id'),
            $paginator->sort('name'),
            $paginator->sort('wmo1'),
            $paginator->sort('lat'),
            $paginator->sort('lon'),
            $paginator->sort('org'),
            $paginator->sort('webname'),
            $paginator->sort('webaktiv'),
            __('Actions', true),
                ));
        echo $tableHeaders;

        $rows = array();
        foreach ($stations AS $station) {
            $actions = $this->Html->link(__('', false), array('action' => 'edit', $station['Station']['id']));
            $actions .= ' ' . $this->Layout->adminRowActions($station['Station']['id']);
            $actions .= ' ' . $this->Html->link(__('Delete', true), array(
                        'action' => 'delete',
                        $station['Station']['id'],
                        'token' => $this->params['_Token']['key'],
                            ), null, __('Are you sure?', true));

            $rows[] = array(
                    $this->Form->checkbox('Station.'.$station['Station']['id'].'.id'),
                    $station['Station']['id'],
                    $this->Html->link($station['Station']['name'], array(
                            'admin' => false,
                            'controller' => 'stations',
                            $station['Station']['id'],
                    )),
                    $station['Station']['wmo1'],
                    $station['Station']['lat'],
                    $station['Station']['lon'],
                    $station['Station']['org'],
                    $station['Station']['webname'],
                    $station['Station']['webaktiv'],
                    $actions,
            );
        }

        echo $this->Html->tableCells($rows);
        echo $tableHeaders;
        ?>
    </table>

</div>

<div class="paging"><?php echo $paginator->numbers(); ?></div>
<div class="counter"><?php echo $paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true))); ?></div>
