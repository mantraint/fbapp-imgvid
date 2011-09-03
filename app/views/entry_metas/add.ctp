<div class="entryMetas form">
<?php echo $this->Form->create('EntryMeta');?>
	<fieldset>
		<legend><?php __('Add Entry Meta'); ?></legend>
	<?php
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

		<li><?php echo $this->Html->link(__('List Entry Metas', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Entries', true), array('controller' => 'entries', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Entry', true), array('controller' => 'entries', 'action' => 'add')); ?> </li>
	</ul>
</div>