<div class="mapViews view">
<h2><?php  __('Map View');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $mapView['MapView']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Map View Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($mapView['MapViewType']['name'], array('controller' => 'map_view_types', 'action' => 'view', $mapView['MapViewType']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $mapView['MapView']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('X1'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $mapView['MapView']['x1']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('X2'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $mapView['MapView']['x2']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Y1'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $mapView['MapView']['y1']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Y2'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $mapView['MapView']['y2']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Map View', true), array('action' => 'edit', $mapView['MapView']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Map View', true), array('action' => 'delete', $mapView['MapView']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $mapView['MapView']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Map Views', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Map View', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Map View Types', true), array('controller' => 'map_view_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Map View Type', true), array('controller' => 'map_view_types', 'action' => 'add')); ?> </li>
	</ul>
</div>
