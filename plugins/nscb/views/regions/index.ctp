<div class="regions index">
	<h2><?php __('Regions');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('code');?></th>
			<th><?php echo $this->Paginator->sort('short_name');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($regions as $region):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $region['Region']['id']; ?>&nbsp;</td>
		<td><?php echo $region['Region']['name']; ?>&nbsp;</td>
		<td><?php echo $region['Region']['code']; ?>&nbsp;</td>
		<td><?php echo $region['Region']['short_name']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $region['Region']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $region['Region']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $region['Region']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $region['Region']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Region', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Provinces', true), array('controller' => 'provinces', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Province', true), array('controller' => 'provinces', 'action' => 'add')); ?> </li>
	</ul>
</div>