<?php echo $this->Html->scriptStart();?>
alert('Photo uploaded successfully!');
parent.Lightview.hide();
FB.ui(
    {
        method: 'feed',
        name: 'Dummy 1',
        caption: 'Dummy 2',
        description: 'Dummy 3',
        message: 'Dummy 4',
        display: 'iframe',
        
    },
    function(response) {
        if (response && response.post_id) {
            alert('Post was published.');
        } else {
            alert('Post was not published.');
        }
    }
);
<?php echo $this->Html->scriptEnd(); ?>