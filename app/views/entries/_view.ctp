<div class="entries view">
<h2><?php  __('Entry');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($entry['User']['id'], array('controller' => 'users', 'action' => 'view', $entry['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Datecreated'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['datecreated']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['status']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Entry', true), array('action' => 'edit', $entry['Entry']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Entry', true), array('action' => 'delete', $entry['Entry']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $entry['Entry']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Entries', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Entry', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Entry Metas', true), array('controller' => 'entry_metas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Entry Meta', true), array('controller' => 'entry_metas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Votes', true), array('controller' => 'votes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Vote', true), array('controller' => 'votes', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Entry Metas');?></h3>
	<?php if (!empty($entry['EntryMeta'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Entry Id'); ?></th>
		<th><?php __('Meta Key'); ?></th>
		<th><?php __('Meta Value'); ?></th>
		<th><?php __('Lastupdatetime'); ?></th>
		<th><?php __('Lastupdateuser'); ?></th>
		<th><?php __('Status'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($entry['EntryMeta'] as $entryMeta):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $entryMeta['id'];?></td>
			<td><?php echo $entryMeta['entry_id'];?></td>
			<td><?php echo $entryMeta['meta_key'];?></td>
			<td><?php echo $entryMeta['meta_value'];?></td>
			<td><?php echo $entryMeta['lastupdatetime'];?></td>
			<td><?php echo $entryMeta['lastupdateuser'];?></td>
			<td><?php echo $entryMeta['status'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'entry_metas', 'action' => 'view', $entryMeta['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'entry_metas', 'action' => 'edit', $entryMeta['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'entry_metas', 'action' => 'delete', $entryMeta['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $entryMeta['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Entry Meta', true), array('controller' => 'entry_metas', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Votes');?></h3>
	<?php if (!empty($entry['Vote'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Entry Id'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Timestamp'); ?></th>
		<th><?php __('Trackip'); ?></th>
		<th><?php __('Status'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($entry['Vote'] as $vote):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $vote['id'];?></td>
			<td><?php echo $vote['entry_id'];?></td>
			<td><?php echo $vote['user_id'];?></td>
			<td><?php echo $vote['timestamp'];?></td>
			<td><?php echo $vote['trackip'];?></td>
			<td><?php echo $vote['status'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'votes', 'action' => 'view', $vote['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'votes', 'action' => 'edit', $vote['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'votes', 'action' => 'delete', $vote['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $vote['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Vote', true), array('controller' => 'votes', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
