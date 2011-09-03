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
		<?php __('CakePHP: the rapid development php framework:'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
        ### css
		echo $this->Html->css('cake.generic');
        echo $this->Html->css('lightview');
        ### js
        echo $this->Html->script('prototype');
        echo $this->Html->script('scriptaculous'); 
        echo $this->Html->script('lightview'); 
        ### facebook Integration JS Library init
        echo $this->Html->script('http://connect.facebook.net/en_US/all.js');
        $fb_script = <<<EOT
        window.fbAsyncInit = function() {
            FB.init({
              appId   : '$fb_appid',
              status  : true, // check login status
              cookie  : true, // enable cookies to allow the server to access the session
              xfbml   : true // parse XFBML
            });
            
            //FB.Canvas.setAutoResize();
            FB.Canvas.setSize({ height: 600 });
        };
EOT;
        //echo $this->Html->scriptBlock("window.fbAsyncInit = function(){ FB.Canvas.setAutoResize(); }");
        echo $this->Html->scriptBlock($fb_script);
		echo $scripts_for_layout;
	?>
</head>
<body>
    <!-- Necessary for Facebook Integration -->
    <div id="fb-root"></div>
    <!-- End -->
	<?php echo $this->Session->flash(); ?>
    <?php echo $this->Session->flash('auth'); ?>
	<?php echo $content_for_layout; ?>
	<?php echo $this->element('sql_dump'); // Visible to administrator only ?>
    <?php echo $this->Js->writeBuffer(); // Necessary for Javascript, invisible to user?>
    <?php echo $this->element('google_analytics'); // Google Analytics ?>
</body>
</html>