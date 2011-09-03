<?php
    //echo $this->Html->scriptStart(array('safe'=>false));
    $func = <<<EOT
    //function formAjax(event){
    formAjax = function(){
        new Ajax.Request('upload', {
            
            onSuccess: function(response){
                
            } 
        });
        //document.getElementById("upload_target").onload = uploadDone();
        //Event.stop(event);
        Lightview.show({
            href: $('EntryUploadForm').action,
            rel: 'ajax',
            options: {
              ajax: {
                evalScripts: true,
                parameters: Form.serialize('EntryUploadForm') // the parameters from the form
              }
            }
        });
    }
    
    formBusy = function(){
        $('upload-busy').show();
    }
    
    uploadDone = function(){
        if(!frames['upload_target'].document.getElementById("uploadForm")) return false;
        var ret = frames['upload_target'].document.getElementById("uploadForm").innerHTML;
        $('uploadForm').update(ret);
        return;
    	var data = eval("("+ret+")"); //Parse JSON // Read the below explanations before passing judgment on me
    	
    	if(data.success) { //This part happens when the image gets uploaded.
    		Lightview.show({
                href: data.href,
                rel: 'ajax',
                title: 'Invite Your Friend',
                options: {
                    topclose: false,
                    closeButton: 'small',
                    height: 510
                }
            });
    	}
    	else if(data.failure) { //Upload failed - show user the reason.
            alert("Upload Failed: " + data.failure);
            console.log(data.failure);
    	}
    }
EOT;
    echo $this->Html->scriptBlock($func);
    echo $this->Js->get('#EntryUploadForm')->event('submit', 'formBusy()', array('stop'=>false));
    echo $this->Js->get('#upload_target')->event('load', 'uploadDone()');
?>
<?php $indicator = $this->Html->image('lightview/loading.gif', array(
    'id'=>'upload-busy', 
    'style'=>'display:none;vertical-align:bottom;height:27px;margin-right:5px')); ?>
<div id="uploadForm">
    <div id="photo-box">
        <h1>Photo</h1>
        <h2>Please select the photo you want to upload.</h2>
        <?php echo $this->Form->create('Entry', array('action' => 'upload', 'type' => 'file', 'class' => 'signup-form', 'target' => 'upload_target')); ?>
     
        <?php
            echo $this->Form->input('user_id', array('type' => 'hidden'));
            
            //echo $this->Form->input('EntryMeta.0.meta_key', array('type' => 'hidden', 'value' => 'media'));
            echo $this->Form->input('Image.img_file', array(
                'label' => 'Upload Photo', 
                'type' => 'file',
                'after' => '<p class="the-notification">Please use GIF, JPG & PNG images of not more than 5MB in size</p>',
            ));
            //echo $this->Html->para('', 'Please use GIF, JPG & PNG images of not more than 5MB in size');
            
            //echo $this->Form->input('EntryMeta.1.meta_key', array('type' => 'hidden', 'value' => 'description'));
            echo $this->Form->input('Image.name', array('label' => 'Add a caption (optional)'));
            //echo $this->Form->input('EntryMeta.2.meta_key', array('type' => 'hidden', 'value' => 'custom1'));
            //echo $this->Form->label('', 'Who are you?');
            //echo $this->Form->radio('Entry.custom1', $options, array('label' => 'Who are you?', 'legend' => false, 'value' => 1 ));
            echo '<div class="input checkbox">';
            echo $this->Form->checkbox('Image.publish', array('hiddenField'=>false, 'checked'=>true));
            echo $this->Html->para('', "Publish a copy to my photo album");
            echo '</div>';
        ?>
        <?php echo $this->Form->end(array('value'=>'Submit', 'before'=>$indicator)); ?>
    </div>
    <div id="video-box">
        <h1>Video</h1>
        <h2>Please select the video you want to upload.</h2>
        <?php echo $this->Form->create('Entry', array('url' => $yt_post, 'type' => 'file', 'class' => 'signup-form', 'target' => 'youtube_target')); ?>
        <input type="hidden" name="token" value="<?php echo $yt_token; ?>">
        <div class="input file">
            <label for="EntryVideoFile">Upload Video</label>
            <input type="file" name="file" id="EntryVideoFile">
            <p class="the-notification">Please use GIF, JPG &amp; PNG images of not more than 5MB in size</p>
        </div>
        <?php
            echo $this->Form->input('user_id', array('type' => 'hidden'));
            echo $this->Form->input('Video.vid_file', array('type' => 'hidden'));
            //echo $this->Form->input('token', array('type' => 'hidden', 'value' => $yt_token));
            
            /*echo $this->Form->input('file', array(
                'label' => 'Upload Video', 
                'type' => 'file',
                'after' => '<p class="the-notification">Please use GIF, JPG & PNG images of not more than 5MB in size</p>',
            ));*/
            
            echo $this->Form->input('Video.name', array('label' => 'Add a caption (optional)'));
        ?>
        <?php echo $this->Form->end(array('value'=>'Submit', 'before'=>$indicator)); ?>
    </div>
</div>
<iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
<iframe id="youtube_target" name="youtube_target" src="" frameborder="1"></iframe>