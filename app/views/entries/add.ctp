<div class="entries form">
<?php echo $this->Form->create('Entry');?>
	<fieldset>
		<legend><?php __('Add Entry'); ?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('datecreated');
		echo $this->Form->input('status');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Entries', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Entry Metas', true), array('controller' => 'entry_metas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Entry Meta', true), array('controller' => 'entry_metas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Votes', true), array('controller' => 'votes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Vote', true), array('controller' => 'votes', 'action' => 'add')); ?> </li>
	</ul>
</div>