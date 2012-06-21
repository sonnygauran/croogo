<div class="mapViewTypes form">
<?php echo $this->Form->create('MapViewType');?>
	<fieldset>
		<legend><?php __('Add Map View Type'); ?></legend>
	<?php
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Map View Types', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Map Views', true), array('controller' => 'map_views', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Map View', true), array('controller' => 'map_views', 'action' => 'add')); ?> </li>
	</ul>
</div>