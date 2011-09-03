<div class="userMetas index">
	<h2><?php __('User Metas');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('meta_key');?></th>
			<th><?php echo $this->Paginator->sort('meta_value');?></th>
			<th><?php echo $this->Paginator->sort('lastupdatetime');?></th>
			<th><?php echo $this->Paginator->sort('lastupdateuser');?></th>
			<th><?php echo $this->Paginator->sort('status');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($userMetas as $userMeta):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $userMeta['UserMeta']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($userMeta['User']['id'], array('controller' => 'users', 'action' => 'view', $userMeta['User']['id'])); ?>
		</td>
		<td><?php echo $userMeta['UserMeta']['meta_key']; ?>&nbsp;</td>
		<td><?php echo $userMeta['UserMeta']['meta_value']; ?>&nbsp;</td>
		<td><?php echo $userMeta['UserMeta']['lastupdatetime']; ?>&nbsp;</td>
		<td><?php echo $userMeta['UserMeta']['lastupdateuser']; ?>&nbsp;</td>
		<td><?php echo $userMeta['UserMeta']['status']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $userMeta['UserMeta']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $userMeta['UserMeta']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $userMeta['UserMeta']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $userMeta['UserMeta']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New User Meta', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>