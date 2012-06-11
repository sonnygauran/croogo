<div class="names form">
<?php echo $this->Form->create('Name');?>
	<fieldset>
		<legend><?php __('Edit Name'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('rc');
		echo $this->Form->input('ufi');
		echo $this->Form->input('uni');
		echo $this->Form->input('lat');
		echo $this->Form->input('long');
		echo $this->Form->input('dms_lat');
		echo $this->Form->input('dms_long');
		echo $this->Form->input('mgrs');
		echo $this->Form->input('jog');
		echo $this->Form->input('fc');
		echo $this->Form->input('dsg');
		echo $this->Form->input('pc');
		echo $this->Form->input('cc1');
		echo $this->Form->input('adm1');
		echo $this->Form->input('pop');
		echo $this->Form->input('elev');
		echo $this->Form->input('cc2');
		echo $this->Form->input('nt');
		echo $this->Form->input('lc');
		echo $this->Form->input('short_form');
		echo $this->Form->input('generic');
		echo $this->Form->input('sort_name_ro');
		echo $this->Form->input('full_name_ro');
		echo $this->Form->input('full_name_nd_ro');
		echo $this->Form->input('sort_name_rg');
		echo $this->Form->input('full_name_rg');
		echo $this->Form->input('full_name_nd_rg');
		echo $this->Form->input('note');
		echo $this->Form->input('modify_date');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Name.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Name.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Names', true), array('action' => 'index'));?></li>
	</ul>
</div>