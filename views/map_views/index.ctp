<div class="mapViews index">
	<h2><?php __('Map Views');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('map_view_type_id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('x1');?></th>
			<th><?php echo $this->Paginator->sort('x2');?></th>
			<th><?php echo $this->Paginator->sort('y1');?></th>
			<th><?php echo $this->Paginator->sort('y2');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($mapViews as $mapView):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $mapView['MapView']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($mapView['MapViewType']['name'], array('controller' => 'map_view_types', 'action' => 'view', $mapView['MapViewType']['id'])); ?>
		</td>
		<td><?php echo $mapView['MapView']['name']; ?>&nbsp;</td>
		<td><?php echo $mapView['MapView']['x1']; ?>&nbsp;</td>
		<td><?php echo $mapView['MapView']['x2']; ?>&nbsp;</td>
		<td><?php echo $mapView['MapView']['y1']; ?>&nbsp;</td>
		<td><?php echo $mapView['MapView']['y2']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $mapView['MapView']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $mapView['MapView']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $mapView['MapView']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $mapView['MapView']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Map View', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Map View Types', true), array('controller' => 'map_view_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Map View Type', true), array('controller' => 'map_view_types', 'action' => 'add')); ?> </li>
	</ul>
</div>