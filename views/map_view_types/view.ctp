<div class="mapViewTypes view">
<h2><?php  __('Map View Type');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $mapViewType['MapViewType']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $mapViewType['MapViewType']['name']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Map View Type', true), array('action' => 'edit', $mapViewType['MapViewType']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Map View Type', true), array('action' => 'delete', $mapViewType['MapViewType']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $mapViewType['MapViewType']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Map View Types', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Map View Type', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Map Views', true), array('controller' => 'map_views', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Map View', true), array('controller' => 'map_views', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Map Views');?></h3>
	<?php if (!empty($mapViewType['MapView'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Map View Type Id'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('X1'); ?></th>
		<th><?php __('X2'); ?></th>
		<th><?php __('Y1'); ?></th>
		<th><?php __('Y2'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($mapViewType['MapView'] as $mapView):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $mapView['id'];?></td>
			<td><?php echo $mapView['map_view_type_id'];?></td>
			<td><?php echo $mapView['name'];?></td>
			<td><?php echo $mapView['x1'];?></td>
			<td><?php echo $mapView['x2'];?></td>
			<td><?php echo $mapView['y1'];?></td>
			<td><?php echo $mapView['y2'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'map_views', 'action' => 'view', $mapView['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'map_views', 'action' => 'edit', $mapView['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'map_views', 'action' => 'delete', $mapView['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $mapView['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Map View', true), array('controller' => 'map_views', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
