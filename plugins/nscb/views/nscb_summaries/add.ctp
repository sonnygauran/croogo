<div class="nscbSummaries form">
<?php echo $this->Form->create('NscbSummary');?>
	<fieldset>
		<legend><?php __('Add Nscb Summary'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Nscb Summaries', true), array('action' => 'index'));?></li>
	</ul>
</div>