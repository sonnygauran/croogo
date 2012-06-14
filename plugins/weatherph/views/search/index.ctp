<div class="names index">
	<h2><?php __('NimaNames');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('NimaName.id');?></th>
        <!--
			<th><?php echo $this->Paginator->sort('rc');?></th>
			<th><?php echo $this->Paginator->sort('ufi');?></th>
			<th><?php echo $this->Paginator->sort('uni');?></th>
        -->
			<th><?php echo $this->Paginator->sort('lat');?></th>
			<th><?php echo $this->Paginator->sort('long');?></th>
            <!--
			<th><?php echo $this->Paginator->sort('dms_lat');?></th>
			<th><?php echo $this->Paginator->sort('dms_long');?></th>
			<th><?php echo $this->Paginator->sort('mgrs');?></th>
			<th><?php echo $this->Paginator->sort('jog');?></th>
            
			<th><?php echo $this->Paginator->sort('fc');?></th>
            -->
			<th><?php echo $this->Paginator->sort('dsg');?></th>
            <!--
			<th><?php echo $this->Paginator->sort('pc');?></th>
            -->
			<th><?php echo $this->Paginator->sort('cc1');?></th>
			<th><?php echo $this->Paginator->sort('adm1');?></th>
            <!--
			<th><?php echo $this->Paginator->sort('pop');?></th>
			<th><?php echo $this->Paginator->sort('elev');?></th>
			<th><?php echo $this->Paginator->sort('cc2');?></th>
			<th><?php echo $this->Paginator->sort('nt');?></th>
			<th><?php echo $this->Paginator->sort('lc');?></th>
			<th><?php echo $this->Paginator->sort('short_form');?></th>
			<th><?php echo $this->Paginator->sort('generic');?></th>
            -->
			<th><?php echo $this->Paginator->sort('sort_name_ro');?></th>
			<th><?php echo $this->Paginator->sort('full_name_ro');?></th>
			<th><?php echo $this->Paginator->sort('full_name_nd_ro');?></th>
			<th><?php echo $this->Paginator->sort('sort_name_rg');?></th>
			<th><?php echo $this->Paginator->sort('full_name_rg');?></th>
			<th><?php echo $this->Paginator->sort('full_name_nd_rg');?></th>
			<th><?php echo $this->Paginator->sort('note');?></th>
			<th><?php echo $this->Paginator->sort('modify_date');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($names as $name):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
        
		<td><?php echo $name['NimaName']['id']; ?>&nbsp;</td>
        <!--
		<td><?php echo $name['NimaName']['rc']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['ufi']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['uni']; ?>&nbsp;</td>
        -->
		<td><?php echo $name['NimaName']['lat']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['long']; ?>&nbsp;</td>
        <!--
		<td><?php echo $name['NimaName']['dms_lat']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['dms_long']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['mgrs']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['jog']; ?>&nbsp;</td>
        
		<td><?php echo $name['NimaName']['fc']; ?>&nbsp;</td>
        -->
		<td><?php echo $name['NimaName']['dsg']; ?>&nbsp;</td>
        <!--
		<td><?php echo $name['NimaName']['pc']; ?>&nbsp;</td>
        -->
		<td><?php echo $name['NimaName']['cc1']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['adm1']; ?>&nbsp;</td>
        <!--
		<td><?php echo $name['NimaName']['pop']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['elev']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['cc2']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['nt']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['lc']; ?>&nbsp;</td>
        
		<td><?php echo $name['NimaName']['short_form']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['generic']; ?>&nbsp;</td>
        -->
		<td><?php echo $name['NimaName']['sort_name_ro']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['full_name_ro']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['full_name_nd_ro']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['sort_name_rg']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['full_name_rg']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['full_name_nd_rg']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['note']; ?>&nbsp;</td>
		<td><?php echo $name['NimaName']['modify_date']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $name['NimaName']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $name['NimaName']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $name['NimaName']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $name['NimaName']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New NimaName', true), array('action' => 'add')); ?></li>
	</ul>
</div>