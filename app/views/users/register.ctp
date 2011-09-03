<?php
    //echo $this->Html->scriptStart(array('safe'=>false));
    $func = <<<EOT
    //function formAjax(event){
    formAjax = function(){
        //Event.stop(event);
        //console.log(this.location.href);
        Lightview.show({
            href: $('UserRegisterForm').action,
            rel: 'ajax',
            options: {
              ajax: {
                evalScripts: true,
                parameters: Form.serialize('UserRegisterForm') // the parameters from the form
              }
            }
        });
    }
EOT;
    echo $this->Html->scriptBlock($func);
    echo $this->Js->get('#UserRegisterForm')->event('submit', 'formAjax()');
    //echo $this->Js->get('#UserRegisterForm')->event('submit', 'formAjax', array('wrap'=>false));
    //echo $this->Html->scriptEnd();
?>
<div class="user-register">
    <h2>Please fill out the form below to complete your registration.</h2>
    <?php echo $this->Form->create('User', array('action' => 'register', 'class' => 'signup-form')); ?>
     
        <?php
            echo $this->Form->input('UserMeta.0.meta_key', array('type'=>'hidden', 'value'=>'full_name'));
            echo $this->Form->input('User.full_name');
            echo $this->Form->input('UserMeta.7.meta_key', array('type'=>'hidden', 'value'=>'ic_no'));
            echo $this->Form->input('User.ic_no');
            echo $this->Form->input('UserMeta.1.meta_key', array('type'=>'hidden', 'value'=>'email'));
            echo $this->Form->input('User.email');
            echo $this->Form->input('UserMeta.2.meta_key', array('type'=>'hidden', 'value'=>'phone'));
            echo $this->Form->input('User.phone', array('label'=>'Mobile Phone'));
            //echo $this->Form->input('User.dob', array('label'=>'Date of Birth', 'type'=>'dateTime'));
            echo $this->Form->input('UserMeta.3.meta_key', array('type'=>'hidden', 'value'=>'gender'));
            echo $this->Form->input('User.gender', array('options'=>array('m'=>'Male', 'f'=>'Female')));
            printf('<div class="input required select %s">', (isset($this->validationErrors['User']['dob'])?'error': ''));
            echo $this->Form->label('Date of Birth');
            echo $this->Form->input('UserMeta.4.meta_key', array('type'=>'hidden', 'value'=>'dob'));
            echo $this->Form->dateTime('User.dob', 'DMY', null, null, array('separator' => ' ', 'maxYear'=>'1999', 'minYear'=>'1800'));
            if(isset($this->validationErrors['User']['dob']))
                printf('<div class="error-message">%s</div>', $this->validationErrors['User']['dob']);
            echo '</div>';
            echo $this->Form->input('UserMeta.5.meta_key', array('type'=>'hidden', 'value'=>'state'));
            echo $this->Form->input('User.state', array('options'=>$states));
            echo $this->Form->input('UserMeta.6.meta_key', array('type'=>'hidden', 'value'=>'city'));
            echo $this->Form->input('User.city');
            printf('<div class="input required checkbox %s">', (isset($this->validationErrors['User']['agree'])?'error': ''));
            echo $this->Form->checkbox('User.agree', array('hiddenField'=>false));
            $terms = $this->Html->link( "Terms & Conditions", array('controller' => 'pages', 'action' => 'terms'),
                array('class'=>' lightview', 'title' => 'Contest Official Terms &amp; Conditions :: :: fullscreen:true, menubar:\'top\', closeButton: \'small\', topclose: false','rel'=>'ajax','escape'=>false)
                        //'title'=>'::::fullscreen:true', 'rel'=>'ajax', 'escape'=>false)
            ); //terms and condition 
            echo $this->Html->para('', "I have read and agree to the Official Contest $terms.");
            if(isset($this->validationErrors['User']['agree']))
                printf('<div class="error-message">%s</div>', $this->validationErrors['User']['agree']);
            echo '</div>';
            //echo $this->Form->input('User.agree', array('type'=>'select', 'multiple'=>'checkbox', 'options'=>array('')));
            
        ?>
    <?php echo $this->Form->end('Register'); ?>
</div>