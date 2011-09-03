<?php echo $this->Html->scriptStart();?>
alert('Photo uploaded successfully!');
parent.Lightview.show({
    //href: '<?php echo Router::url(array('controller'=>'entries', 'action'=>'inviteFriends'));?>',
    href: '<?php echo Router::url(sprintf('/entries/inviteFriends/para:%s', 'gallery'));?>',
    rel: 'iframe',
    title: 'Invite Your Friends',
    options: {
        closeButton: 'small',
        topclose: false,
    }
});
parent.FB.ui(
    {
        method: 'feed',
        name: '<?php echo addslashes($fb_config['name']);?>',
        caption: '<?php echo addslashes($fb_config['caption']);?>',
        description: 'caption: <?php echo addslashes($data['description']);?>',
        message: '<?php echo addslashes($fb_config['message']);?>',
        link: '<?php echo $data['link'];?>',
        picture: '<?php echo $data['picture'];?>',
        app_id: '<?php echo $data['appid'];?>',
        display: 'iframe'
    },
    function(response) {

    }
);
<?php echo $this->Html->scriptEnd(); ?>