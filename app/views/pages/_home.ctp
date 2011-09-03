<?php
    echo $this->Html->scriptStart(array('inline'=>'false'));
    $js_func = <<<EOT
    function signUpAjax(event){
        Event.stop(event);
        Lightview.show({
            href: this.href,
            rel: 'ajax',
            options: {
                topclose: true,
                ajax: {
                    evalScripts: true
                }
            }
        });
    }
    
    function inviteAjax(event){
        Event.stop(event);
        Lightview.show({
            href: this.href,
            rel: 'ajax',
            title: 'Invite Your Friend',
            options: {
                topclose: false,
                closeButton: 'small',
                height: 510
            }
        });
    }
    /*
    function formAjax(event){
        Event.stop(event);
        Lightview.show({
            href: this.action,
            rel: 'ajax',
            options: {
              title: 'results',
              ajax: {
                evalScripts: true,
                parameters: Form.serialize('UserRegisterForm') // the parameters from the form
              }
            }
        });
    }
    */
EOT;
    echo $this->Html->scriptBlock($js_func, array('inline'=>false));
    /*$lightview = <<<EOT
    Event.stop(event);
    Lightview.show({
        href: this.href,
        rel: 'ajax',
        options: {
            topclose: true,
            ajax: {
                onComplete: function(){
                // once the request is complete we observe the form for a submit
                //$$('.signup-form').observe('submit', submitAjaxFormDemonstration);
                    $oncomplete
                }
            }
        }
    });
EOT;*/
    //echo $this->Js->get('#signup-link')->event('click', 'signUpAjax', array('wrap'=>false));
    echo $this->Js->domReady($this->Js->get('.signup-link')->each('item.observe( \'click\', signUpAjax)'));
    //echo $this->Js->get('#invite-link')->event('click', 'inviteAjax', array('wrap'=>false));
    echo $this->Html->scriptEnd();
?>
<ul>
    <!-- <li><?php echo $this->Html->link("Enter Contest", array('controller' => 'users', 'action' => 'register')); ?></li> -->
    <li>
        <?php echo $this->Html->link("Enter Contest", array('controller' => 'users', 'action' => 'register'), 
            array('class'=>'signup-link')); ?>
    </li>
    <li><?php echo $this->Html->link("View Gallery", array('controller' => 'entries', 'action' => 'gallery')); ?></li>
    <!--
    <li>
        <?php echo $this->Html->link("Invite Friends", array('controller' => 'entries', 'action' => 'inviteFriends'), 
            array('id'=>'invite-link')); ?>
    </li>
    -->
    <li>
        <?php echo $this->Html->link("Invite Friends", array('controller' => 'entries', 'action' => 'inviteFriends'), 
            array('class' => 'lightview', 'title' => 'Invite Your Friend :: :: height: 510, closeButton: "small", topclose: false')); ?>
    </li>
    <!--
    <li>
        <?php echo $this->Html->link("Invite Friends 2", 'javascript:void(0)', array('onclick' => "FB.ui({method: 'apprequests', message: 'Dummy message'})")); ?>
    </li>
    <li>
        <?php echo $this->Html->link("Invite Friends 3", array('controller' => 'pages', 'action' => 'invite_friend'), 
            array('class' => 'lightview', 'title' => 'Invite Your Friend :: :: height: 510, closeButton: "small", topclose: false')); ?>
    </li>
    -->
</ul>