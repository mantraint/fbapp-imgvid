<div class="entryMetas index">
	<h2><?php __('Entry Metas');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('entry_id');?></th>
			<th><?php echo $this->Paginator->sort('meta_key');?></th>
			<th><?php echo $this->Paginator->sort('meta_value');?></th>
			<th><?php echo $this->Paginator->sort('lastupdatetime');?></th>
			<th><?php echo $this->Paginator->sort('lastupdateuser');?></th>
			<th><?php echo $this->Paginator->sort('status');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($entryMetas as $entryMeta):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $entryMeta['EntryMeta']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($entryMeta['Entry']['id'], array('controller' => 'entries', 'action' => 'view', $entryMeta['Entry']['id'])); ?>
		</td>
		<td><?php echo $entryMeta['EntryMeta']['meta_key']; ?>&nbsp;</td>
		<td><?php echo $entryMeta['EntryMeta']['meta_value']; ?>&nbsp;</td>
		<td><?php echo $entryMeta['EntryMeta']['lastupdatetime']; ?>&nbsp;</td>
		<td><?php echo $entryMeta['EntryMeta']['lastupdateuser']; ?>&nbsp;</td>
		<td><?php echo $entryMeta['EntryMeta']['status']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $entryMeta['EntryMeta']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $entryMeta['EntryMeta']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $entryMeta['EntryMeta']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $entryMeta['EntryMeta']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Entry Meta', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Entries', true), array('controller' => 'entries', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Entry', true), array('controller' => 'entries', 'action' => 'add')); ?> </li>
	</ul>
</div>