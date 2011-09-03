<?php
    ### js
    echo $this->Html->script('prototype');
    echo $this->Html->script('scriptaculous'); 
    echo $this->Html->script('lightview'); 
    ### facebook Integration JS Library init
    echo $this->Html->script('http://connect.facebook.net/en_US/all.js');
?>
<?php echo $this->Session->flash(); ?>
<?php echo $this->Session->flash('auth'); ?>
<?php echo $content_for_layout; ?>
<?php echo $this->element('sql_dump'); // Visible to administrator only ?>
<?php echo $this->Js->writeBuffer(); // Necessary for Javascript, invisible to user?>