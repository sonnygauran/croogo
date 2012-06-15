<div class="nscbSummaries view">
<h2><?php  __('Nscb Summary');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $nscbSummary['NscbSummary']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $nscbSummary['NscbSummary']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Value'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $nscbSummary['NscbSummary']['value']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Nscb Summary', true), array('action' => 'edit', $nscbSummary['NscbSummary']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Nscb Summary', true), array('action' => 'delete', $nscbSummary['NscbSummary']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $nscbSummary['NscbSummary']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Nscb Summaries', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Nscb Summary', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
