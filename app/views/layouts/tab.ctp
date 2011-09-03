<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('ZTE Malaysia'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
        ### css
        echo $this->Html->css('style-contest-tab');
        echo $this->Html->css('lightview');
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
      appId   : '$fb_appid',
      status  : true, // check login status
      cookie  : true, // enable cookies to allow the server to access the session
      xfbml   : true // parse XFBML
    });
    
    //FB.Canvas.setAutoResize();
    FB.Canvas.setSize({ height: 680 });
};
    <?php
        echo $this->Js->domReady($this->Js->get('.signup-link')->each('item.observe( \'click\', signUpAjax)'));
        echo $this->Html->scriptEnd();
		echo $scripts_for_layout;
	?>
</head>
<body>
    <!-- Necessary for Facebook Integration -->
    <div id="fb-root"></div>
    <?php echo $content_for_layout; ?>
    <?php echo $this->element('google_analytics'); // Google Analytics ?>
</body>
</html>