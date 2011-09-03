<div><img src="<?php echo Router::url('/img/poster.jpg');?>" /></div>
<div class="tab-bar">
    <p>Dreaming to go DUO? Well, now you can with MyEG! Just "Like" us now and click enter to find out how you could score awesome VVIP* tix for absolutely FREE!</p>
    <small>* Terms and conditions apply.</small>
    <?php if($liked) : ?>
        <a href="<?php echo $fb_config['canvas'];?>" class="is-fans" target="_parent">
			<img src="<?php echo Router::url('/img/enter-btn.jpg');?>" />
        </a>
    <?php else : ?>
        <!-- <img src="<?php echo Router::url('/img/like-btn.jpg');?>" /> -->
    <?php endif;?>
</div>