<div class="mapViews form">
<?php echo $this->Form->create('MapView');?>
	<fieldset>
		<legend><?php __('Add Map View'); ?></legend>
	<?php
		echo $this->Form->input('map_view_type_id');
		echo $this->Form->input('name');
		echo $this->Form->input('x1');
		echo $this->Form->input('x2');
		echo $this->Form->input('y1');
		echo $this->Form->input('y2');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Map Views', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Map View Types', true), array('controller' => 'map_view_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Map View Type', true), array('controller' => 'map_view_types', 'action' => 'add')); ?> </li>
	</ul>
</div>