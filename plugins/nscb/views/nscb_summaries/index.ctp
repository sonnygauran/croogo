<div class="nscbSummaries index">
	<h2><?php __('Nscb Summaries');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('value');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($nscbSummaries as $nscbSummary):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $nscbSummary['NscbSummary']['id']; ?>&nbsp;</td>
		<td><?php echo $nscbSummary['NscbSummary']['name']; ?>&nbsp;</td>
		<td><?php echo $nscbSummary['NscbSummary']['value']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $nscbSummary['NscbSummary']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $nscbSummary['NscbSummary']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $nscbSummary['NscbSummary']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $nscbSummary['NscbSummary']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Nscb Summary', true), array('action' => 'add')); ?></li>
	</ul>
</div>