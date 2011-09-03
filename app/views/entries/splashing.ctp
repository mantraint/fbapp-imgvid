<div class="the-title">
	<img src="<?php echo Router::url('/img/ctab/the-title.jpg');?>" />
</div> <!-- the-title -->

<div class="the-body">
    
    <div class="the-phone">
    	<img src="<?php echo Router::url('/img/ctab/the-phone.png');?>" />
        
        <div class="the-content">
            <p>What would you do with the all-new ZTE Blade Android smart phone in your hands?</p>
            <p>Take a photo of you in action with your imaginary ZTE Blade and you might just win the phone and cold hard cash!</p>
		</div> <!-- the-content -->
                
        <div class="foot-menu">
        <?php if($liked) : ?>
            <a href="http://apps.facebook.com/bladeinyourhands" class="is-fans" target="_parent">
				Join Now!
            </a>
        <?php else : ?>
            <p class="b-fans">Like us now to get the Blade in your hands!</p>
        <?php endif;?>
		</div> <!-- foot-menu -->
        
    </div> <!-- the-phone -->
    
</div> <!-- the-body -->


    <p class="the-footer">ZTE Corporation (Malaysia) Sdn Bhd &copy; 2011
    <span style="float: right; margin-right:10px;">
    <?php echo $this->Html->link(
                        "Terms &amp; Conditions", 
                        array('controller' => 'pages', 'action' => 'terms'),
                        array('class'=>'lightview', 
                                'title' => 'Contest Official Terms &amp; Conditions :: :: fullscreen:true, menubar:\'top\', closeButton: \'small\', topclose: false', 'target'=>'_blank', 'rel'=>'ajax','escape'=>false)

                        ); //terms and condition 
    ?></span> 
    </p> <!-- the-footer -->