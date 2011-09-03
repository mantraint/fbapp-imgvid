<?php echo $this->Html->scriptStart();?>
window.fbAsyncInit = function() {
    FB.init({
      appId   : '<?php echo $fb_config['appId'];?>',
      status  : true, // check login status
      cookie  : true, // enable cookies to allow the server to access the session
      xfbml   : true // parse XFBML
    });
};

function shareThis(){
    FB.ui(
        {
            method: 'feed',
            name: '<?php echo addslashes($fb_config['name']);?>',
            caption: '<?php echo addslashes($fb_config['caption']);?>',
            description: '<?php echo addslashes($entry['Image']['name']);?>',
            message: '',
            link: '<?php echo $fb_config['canvas'];?>entries/vote/<?php echo $entry['Entry']['id'];?>',
            picture: '<?php echo Router::url('/img/uploads/images/thumb/small/'.$entry['Image']['img_file'], true);?>',
            display: 'iframe'
        },
        function(response) {
            if (response && response.post_id) {
                //alert('Post was published.');
            } else {
                //alert('Post was not published.');
            }
        }
    );
}

function voteThis(){
    FB.getLoginStatus(function(response) {
        if (response.session) {
            // logged in and connected user, someone you know
            if($('ImagePublish'))
                post = $('ImagePublish').checked ? 1 : 0;
            else
                post = 0;
            parent.Lightview.show({
                rel: 'ajax',
                href: '<?php echo Router::url('/entries/vote/'.$entry['Entry']['id']);?>/post:'+post
            }); 
        } else {
            // no user session available, someone you dont know
            FB.login(function(response) {
                if (response.session) {
                    if (response.perms) {
                        // user is logged in and granted some permissions.
                        // perms is a comma separated list of granted permissions
                    } else {
                        // user is logged in, but did not grant any permissions
                    }
                } else {
                    // user is not logged in
                }
            }, {perms:'<?php echo $fb_config['perms'];?>'});
        }
    });       
}

<?php echo $this->Html->scriptEnd();?>
<?php echo $this->Html->css('style-vote-window'); ?>

<div id="fb-root"></div> 
<div class="the-vote-window" style="text-align: center;">

    <div class="vote-share">
            <?php
            echo $this->Form->button('Share', array('onclick'=>'shareThis()', 'class' => 'share'));
            echo $this->Form->button('Vote', array('onclick'=>'voteThis()', 'class' => 'vote'));
            //echo $this->Html->link('Share', 'javascript:void(0)', array('onclick'=>'shareThis('.$entry['Entry']['id'].')', 'class'=>'share'));
            //echo $this->Html->link('Vote', array('controller'=>'entries', 'action'=>'vote', $entry['Entry']['id']), array('rel'=>'ajax', 'class'=>'lightview vote'));
            ?>
            <p>
            <?php echo $this->Form->checkbox('Image.publish', array('hiddenField'=>false, 'checked'=>true));?>
                Post to my wall upon voting
            </p>
    </div> <!-- vote-share-->
    
    <div class="image-cell">
        <?php echo $this->Html->image('uploads/images/'.$entry['Image']['img_file'], array(
                'alt' => $entry['Image']['name'].' | '.$fb_config['name'],
                'style' => 'border: 1px solid #333; padding: 2px;'
            ));
        ?>
    </div> <!-- image-cell -->
    <?php if($this->Session->read('Auth.User')):?>
    <script>
        document.observe("dom:loaded", function(){
            $$('.userid').each(function(o){
                FB.api('/'+o.innerHTML, function(response) {
                    o.replace(response.name);
                });
            })
        })
    </script>
    <div class="related">
    	<h3><?php __('Related Votes');?></h3>
    	<?php if (!empty($entry['Vote'])):?>
    	<table cellpadding = "3" cellspacing = "3" style="font-size: 10px;">
    	<tr>
    		<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('timestamp');?></th>
			<th><?php echo $this->Paginator->sort('trackip');?></th>
			<!-- <th><?php echo $this->Paginator->sort('status');?></th> -->
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
        		<td>
        			<?php //echo $this->Html->link($vote['User']['id'], array('controller' => 'users', 'action' => 'view', $vote['User']['id'])); ?>
                    <a href="http://www.facebook.com/profile.php?id=<?php echo $vote['user_id'];?>" target="facebook" style="text-decoration: none;">
                        <img src="http://graph.facebook.com/<?php echo $vote['user_id'];?>/picture" border="0">
                    </a>
                    <span class="userid"><?php echo $vote['user_id'];?></span>
        		</td>
        		<td><?php echo $vote['timestamp']; ?>&nbsp;</td>
        		<td><?php echo $vote['trackip']; ?>&nbsp;</td>
        		<td class="actions">
        			<?php echo $this->Html->link(__('Delete', true), array('controller' => 'votes', 'action' => 'delete', $vote['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $vote['id'])); ?>
        		</td>
    		</tr>
    	<?php endforeach; ?>
    	</table>
    <?php endif; ?>
    </div>
    <?php endif;?>
</div> <!-- image-gallery -->