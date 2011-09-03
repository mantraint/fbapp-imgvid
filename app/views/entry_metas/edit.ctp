<div class="entryMetas form">
<?php echo $this->Form->create('EntryMeta');?>
	<fieldset>
		<legend><?php __('Edit Entry Meta'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('entry_id');
		echo $this->Form->input('meta_key');
		echo $this->Form->input('meta_value');
		echo $this->Form->input('lastupdatetime');
		echo $this->Form->input('lastupdateuser');
		echo $this->Form->input('status');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('EntryMeta.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('EntryMeta.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Entry Metas', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Entries', true), array('controller' => 'entries', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Entry', true), array('controller' => 'entries', 'action' => 'add')); ?> </li>
	</ul>
</div>