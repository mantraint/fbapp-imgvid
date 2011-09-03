<?php echo $this->Html->scriptStart(array('inline'=>false)); ?>
function shareThis(i){
    FB.ui(
        {
            method: 'feed',
            name: '<?php echo addslashes($fb_config['name']);?>',
            caption: '<?php echo addslashes($fb_config['caption']);?>',
            description: 'caption: ' + $$('.entry-'+i)[0].alt.split(' | ')[0],
            message: '',
            link: '<?php echo $fb['canvas'];?>entries/vote/'+i,
            picture: $$('.entry-'+i)[0].src,
            display: 'iframe'
        },
        function(response) {
            if (response && response.post_id) {
                //alert('Post was published.');
            } else {
                //alert('Post was not published.');
            }
        }
    );
}
<?php echo $this->Html->scriptEnd(); ?>
<h2><?php __('Images');?></h2>
<div id="message-box"></div>
<div class="image-gallery">
<?php foreach ($images as $image):?>
    <div class="image-cell">
        <?php
            $img = $this->Html->image('uploads/images/thumb/small/' . $image['Image']['img_file'], array( 'alt' => sprintf("%s by %s | %s", $image['Image']['name'], $image['UserMeta']['meta_value'], $fb_config['name']), 'class' => 'entry-'.$image['Entry']['id'] ));
            echo $this->Html->link( $img, 
                //Router::url(array('controller'=>'entries', 'action'=>'view', $image['Entry']['id'])),
                '/entries/view/'.$image['Entry']['id'],
                //'/img/uploads/images/'.$image['Image']['img_file'],
                array(
                    'class' => 'lightview the-thumbnails',
                    //'rel' => 'gallery[zte]',
                    'rel' => 'set[zte]',
                    'title' => $image['Image']['name'].' :: '.$image['UserMeta']['meta_value'].' :: '.'width:620',
                    'escape' => false,                            
            ));    
        ?>
        <div class="vote-share">
        <?php
            echo $this->Html->link('Share', 'javascript:void(0)', array('onclick'=>'shareThis('.$image['Entry']['id'].')', 'class'=>'share'));
            echo $this->Html->link('Vote', array('controller'=>'entries', 'action'=>'vote', $image['Entry']['id']), array('rel'=>'ajax', 'class'=>'lightview vote'));
        ?>
        </div>
        <div class="image-buttons">
        <?php
            /*echo $ajax->link(
                'Vote', 
                array('controller'=>'entries', 'action'=>'vote', $image['Entry']['id']),
                //array('complete'=>'voteDone()')
                array('update' => 'message-box')
            );*/
            //echo $this->Html->link('Vote', array('controller'=>'entries', 'action'=>'vote', $image['Entry']['id']), array('rel'=>'ajax', 'class'=>'lightview'));
            //echo $this->Html->link('Vote', array('controller'=>'entries', 'action'=>'vote', $image['Entry']['id']), array('class'=>'vote-lnk'));
            //echo $this->Html->link('Share', 'javascript:void(0)', array('onclick'=>'shareThis('.$image['Entry']['id'].')'));
            echo $this->Html->para('vote-count', $image[0]['vote']);
            echo $this->Html->para('image-author', sprintf('by %s', substr($image['UserMeta']['meta_value'], 0, 20)))
        ?>
        </div>
    </div>
<?php endforeach;?>
</div>
<div class="bottom-bar">
    <div class="top"></div>
    <div class="body">
        <div class="gallery-information">
            <span>Sort By</span>
        <?php
        $this->Paginator->options(array(
            'update' => '#content', 
            'evalScripts' => true,
            'before' => $this->Js->get('#lv_overlay')->effect('show', array('buffer'=>false)),
            'complete' => $this->Js->get('#lv_overlay')->effect('hide', array('buffer'=>false)),
        ));
        
        /* KENT */
        $page = isset($this->params['named']['page']) ? $this->params['named']['page'] : 1;
        //echo $this->Html->link('Most Popular', array('controller'=>'entries', 'action'=>'gallery', 'page'=>$page));
        echo $ajax->link('Most Popular', array('controller'=>'entries', 'action'=>'gallery', 'page'=>$page), array(
            'update'=>'content', 
            'before' => $this->Js->get('#lv_overlay')->effect('show', array('buffer'=>false)),
            'complete' => $this->Js->get('#lv_overlay')->effect('hide', array('buffer'=>false)),
            'class' => 'sort-links first-item'
        ));
        echo $this->Paginator->sort('Most Recent', 'Entry.datecreated', array('direction'=>'desc', 'class' => 'sort-links'));
        echo $this->Paginator->sort('Name', 'UserMeta.meta_value', array('direction'=>'asc', 'class' => 'sort-links'));
        /*KENT*/
        ?>	
        </div>
        <div class="paging">
        	<?php echo $this->Paginator->prev('<', array(), null, array('class'=>'disabled previous'));?>
          	
              <?php echo $this->Paginator->numbers(array('separator'=>''));?>
        
        	<?php echo $this->Paginator->next('>', array(), null, array('class' => 'disabled next'));?>
        </div>
    </div>
    <div class="btm"></div>
</div>