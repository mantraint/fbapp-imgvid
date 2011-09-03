<?php echo $this->Html->scriptBlock("parent.Lightview.hide()"); ?>
<?php 
if(isset($this->params['named']['para'])){
    echo $this->Html->scriptBlock("parent.top.location.href='{$fb_config['canvas']}entries/".$this->params['named']['para']."/page:1/sort:Entry.datecreated/direction:desc'");
}
?>