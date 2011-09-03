<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __($fb_config['name']); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
        ### css
		//echo $this->Html->css('cake.generic');
        echo $this->Html->css('lightview');
        echo $this->Html->css('style');
        ### js
        echo $this->Html->script('prototype');
        echo $this->Html->script('scriptaculous'); 
        echo $this->Html->script('lightview'); 
        ### facebook Integration JS Library init
        echo $this->Html->script('http://connect.facebook.net/en_US/all.js');
        echo $this->Html->scriptStart();
    ?>
window.fbAsyncInit = function() {
    FB.init({
      appId   : '<?php echo $fb_appid;?>',
      status  : true, // check login status
      cookie  : true, // enable cookies to allow the server to access the session
      xfbml   : true // parse XFBML
    });
    
    //FB.Canvas.setAutoResize();
    FB.Canvas.setSize({ height: 600 });
};

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
    <?php
        echo $this->Js->domReady($this->Js->get('.join-link')->each('item.observe( \'click\', signUpAjax)'));
        if(!isset($liked) || !$liked){?>
document.observe('lightview:loaded', function() {
  Lightview.show('#like-button',{
<?php if($this->Session->read('User.fbid')):?>
    overlayClose:false,
    keyboard:false,
    closeButton:false,
    topclose:false
<?php endif;?>
  });
});
FB.Event.subscribe('edge.create', function(response){
  Lightview.hide();
});
    <?php }
        echo $this->Html->scriptEnd();
		echo $scripts_for_layout;
	?>
</head>
<body>
    <!-- Necessary for Facebook Integration -->
    <div id="fb-root"></div>
    <!-- End -->
    <div class="the-header">
        <div class="the-navigation">
        	<a href="<?php echo $fb_config['canvas'];?>" class="nav-std home here" target="_parent"><span class="hidden-text">Home</span></a>
            <?php 
                echo $this->Html->link("<span class=\"hidden-text\">HowTo</span>", 
                    array('controller' => 'pages', 'action' => 'how'),
                    array('class'=>'nav-std how signup-link lightview', 
                        'title'=>'::::fullscreen:true, menubar:\'top\', closeButton: \'small\', topclose: false', 'rel'=>'ajax', 
                        'escape'=>false
                    )
                ); //how to win
                echo $this->Html->link("<span class=\"hidden-text\">Join</span>", 
                    array('controller' => 'users', 'action' => 'register'), 
                    array('class'=>'nav-std join signup-link join-link', 'escape'=>false)
                ); //join contest.register
                echo $this->Html->link("<span class=\"hidden-text\">Invite</span>", 
                    array('controller' => 'entries', 'action' => 'inviteFriends'), 
                    array('class'=>'nav-std invite lightview', 
                        'title' => 'Invite Your Friends :: :: height: 510, closeButton: \'small\', topclose: false', 
                        'escape' => false
                    )
                ); //invite friends
                echo $this->Html->link("<span class=\"hidden-text\">Gallery</span>", 
                    rtrim($fb_config['canvas'],"/").Router::url(array('controller'=>'entries', 'action'=>'gallery', 'base'=>false, 'page' => 1, 'sort' => 'Entry.datecreated', 'direction' => 'desc')),
                    array('class'=>'nav-std vote', 'target'=>'_parent', 'escape'=>false)
                ); //view gallery
            ?>
        </div> <!-- the-navigation -->
       	<div class="the-logo">
            <a href="http://www.facebook.com/clubmyeg" target="_top">
                <img src="<?php echo Router::url('/img/the-logo.png');?>" />
            </a>
        </div> <!-- the-logo -->
    </div> <!-- the-header -->
	<div class="the-body">
		<div id="content" class="the-content">
			<?php echo $this->Session->flash(); ?>
            <?php echo $this->Session->flash('auth'); ?>
			<?php echo $content_for_layout; ?>
		</div>
        <div class="the-footer">
            <div class="zte-copyright">
                <p>MyEG Services Bhd &copy; 2011 
                <?php echo $this->Html->link(
                    "Terms &amp; Conditions", 
                    array('controller' => 'pages', 'action' => 'terms'),
                    array('class'=>'lightview', 
                            'title' => 'Contest Official Terms &amp; Conditions :: :: fullscreen:true, menubar:\'top\', closeButton: \'small\', topclose: false','rel'=>'ajax','escape'=>false)

                    ); //terms and condition 
                ?>
                </p>
            </div>
                
        <?php if(!isset($liked) || !$liked) : ?>
            <div id="like-button" class="the-like-button">
                
                <p>Please Like Us to Proceed!</p>

                <fb:like-box href="http://www.facebook.com/clubmyeg" width="270" show_faces="false" border_color="" stream="false" header="false"></fb:like-box>
            </div> <!-- the-like-button -->
        <? else :?>
            <!-- you have already liked! -->                
        <? endif; ?>
                
        </div> <!-- the-footer -->
        <div style="clear:both; padding-top:10px;"></div>
    </div> <!-- the-body -->
	<?php echo $this->element('sql_dump'); // Visible to administrator only ?>
    <?php echo $this->Js->writeBuffer(); // Necessary for Javascript, invisible to user?>
    <?php echo $this->element('google_analytics'); // Google Analytics ?>
</body>
</html>