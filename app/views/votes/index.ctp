<script>
  window.fbAsyncInit = function() {
    FB.init({appId: '<?php echo $fb_appid;?>', status: true, cookie: true,
             xfbml: true});
  };
  document.observe("dom:loaded", function(){
    $$('.userid').each(function(o){
        FB.api('/'+o.innerHTML, function(response) {
            o.replace(response.name);
        });
    })
  })
</script>
<div id="fb-root"></div>
<div class="votes index">
	<h2><?php __('Votes');?></h2>
	<table border="1" width="90%">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('entry_id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('timestamp');?></th>
			<th><?php echo $this->Paginator->sort('trackip');?></th>
			<!-- <th><?php echo $this->Paginator->sort('status');?></th> -->
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($votes as $vote):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $vote['Vote']['id']; ?>&nbsp;</td>
		<td>
            <?php
            $img = $this->Html->image('/entries/thumb/'.$vote['Entry']['id'], array('style'=>'height:50px'));
            echo $this->Html->link( $img, 
                //Router::url(array('controller'=>'entries', 'action'=>'view', $image['Entry']['id'])),
                array('controller' => 'entries', 'action' => 'view', $vote['Entry']['id']),
                //'/img/uploads/images/'.$image['Image']['img_file'],
                array(
                    'escape' => false,                            
            ));   
            ?>
			<?php //echo $this->Html->link($vote['Entry']['id'], array('controller' => 'entries', 'action' => 'view', $vote['Entry']['id'])); ?>
		</td>
		<td>
			<?php //echo $this->Html->link($vote['User']['id'], array('controller' => 'users', 'action' => 'view', $vote['User']['id'])); ?>
            <a href="http://www.facebook.com/profile.php?id=<?php echo $vote['Vote']['user_id'];?>" target="facebook" style="text-decoration: none;">
                <img src="http://graph.facebook.com/<?php echo $vote['Vote']['user_id'];?>/picture" border="0">
            </a>
            <span class="userid"><?php echo $vote['Vote']['user_id'];?></span>
		</td>
		<td><?php echo $vote['Vote']['timestamp']; ?>&nbsp;</td>
		<td><?php echo $vote['Vote']['trackip']; ?>&nbsp;</td>
		<!-- <td><?php echo $vote['Vote']['status']; ?>&nbsp;</td> -->
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $vote['Vote']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $vote['Vote']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $vote['Vote']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $vote['Vote']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Vote', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Entries', true), array('controller' => 'entries', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Entry', true), array('controller' => 'entries', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>