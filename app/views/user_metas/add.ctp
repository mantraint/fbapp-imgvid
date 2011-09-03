<div class="userMetas form">
<?php echo $this->Form->create('UserMeta');?>
	<fieldset>
		<legend><?php __('Add User Meta'); ?></legend>
	<?php
		echo $this->Form->input('user_id');
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

		<li><?php echo $this->Html->link(__('List User Metas', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>