<?php echo $this->Html->scriptStart();?>
var status = '<?php echo $param['status'];?>';
var id = '<?php echo $param['id'];?>';

switch(status){
    case 200:
        parent.document.getElementById('VideoVidFile').value = id;
        
        break;
    default:
        alert('Error occured during video upload. Please try again.');
        break;
}
<?php echo $this->Html->scriptEnd(); ?>