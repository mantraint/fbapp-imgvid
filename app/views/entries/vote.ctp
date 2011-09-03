<?php if(isset($data)):?>
<?php echo $this->Html->scriptStart(array('inline'=>false)); ?>
function shareThis(i){
    FB.getLoginStatus(function(response) {
        if (response.session) {
            FB.ui(
                {
                    method: 'feed',
                    name: '<?php echo addslashes($fb_config['name']);?>',
                    caption: '<?php echo addslashes($fb_config['caption']);?>',
                    description: '<?php echo addslashes($data['Image']['name']);?>',
                    message: '',
                    link: '<?php echo $fb_config['canvas'];?>entries/vote/'+i,
                    picture: '<?php echo Router::url('/img/uploads/images/thumb/small/'.$data['Image']['img_file'], true);?>',
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
        } else {
            alert('You need to grant access to the application in order to share!');
        }
    });
}
function voteThis(){
    FB.getLoginStatus(function(response) {
        if (response.session) {
            // logged in and connected user, someone you know
            if($('ImagePublish'))
                post = $('ImagePublish').checked ? 1 : 0;
            else
                post = 0;
            Lightview.show({
                rel: 'ajax',
                href: '<?php echo Router::url(array('controller'=>'entries', 'action'=>'vote', $data['Entry']['id']));?>/post:'+post
            }); 
        } else {
            // no user session available, someone you dont know
            FB.login(function(response) {
                if (response.session) {
                    if (response.perms) {
                        // user is logged in and granted some permissions.
                        // perms is a comma separated list of granted permissions
                        // logged in and connected user, someone you know
                        if($('ImagePublish'))
                            post = $('ImagePublish').checked ? 1 : 0;
                        else
                            post = 0;
                        Lightview.show({
                            rel: 'ajax',
                            href: '<?php echo Router::url(array('controller'=>'entries', 'action'=>'vote', $data['Entry']['id']));?>/post:'+post+'/uid:'+response.session.uid
                        }); 
                    } else {
                        // user is logged in, but did not grant any permissions
                        alert('You need to grant access to the application in order to vote!');
                    }
                } else {
                    // user is not logged in
                    alert('You need to grant access to the application in order to vote!');
                }
            }, {perms:'<?php echo $fb_config['perms'];?>'});
        }
    });
}
<?php echo $this->Html->scriptEnd(); ?>
<?php if(!($this->Session->read('User.fbid'))):?>
<div class="message special">
    In order to use Blade In Your Hands, you must grant the following app permission request.<br>
    <a href="<?php echo $login;?>" target="_top">Go to App</a>
    <!--
    <fb:login-button show-faces="false" width="200" max-rows="1" display="iframe" perms="<?php echo $fb_config['perms'];?>"></fb:login-button>
    -->
</div>
<?php endif;?>
<div class="image-gallery the-vote">
    <div class="image-cell">
    
        
        <?php $img = $this->Html->image('uploads/images/'.$data['Image']['img_file'], array(
                'alt' => $data['Image']['name'].' | ZTE Malaysia Blade In Your Hands',
                'style' => 'max-height:300px'
            ));
            echo $this->Html->link( $img, '/img/uploads/images/'.$data['Image']['img_file'], array(
                'class' => 'lightview the-thumbnails',
                'escape' => false,
            ));
        ?>
    
    <p class="image-caption"><?php echo $data['Image']['name'];?></p>
    <p class="image-author"><?php echo 'by '.$data['UserMeta']['meta_value'];?></p>
    
    <div class="vote-share">
            <?php
            echo $this->Html->link('Share', 'javascript:void(0)', array('onclick'=>'shareThis('.$data['Entry']['id'].')', 'class'=>'share'));
            echo $this->Html->link('Vote', 'javascript:void(0)', array('onclick'=>'voteThis()', 'class'=>'vote'));
            ?>
            <?php if(($this->Session->read('User.fbid'))):?>
            <p style="margin-top: 10px;">
            <?php echo $this->Form->checkbox('Image.publish', array('hiddenField'=>false, 'checked'=>true));?>
                Post to my wall upon voting
            </p>
            <?php endif;?>
    </div> <!-- vote-share from vote.ctp-->
    </div> <!-- image-cell from vote.ctp-->
</div> <!-- image-gallery -->
<?php endif; ?>