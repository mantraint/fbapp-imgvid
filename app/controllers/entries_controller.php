<?php
class EntriesController extends AppController {
    var $helpers = array ('Html','Form','Paginator');
    var $name = 'Entries';
    var $paginate = array(
        'EntryMeta' => array(
            'fields' => array('Image.*', 'Entry.*', 'UserMeta.*', 'COUNT(Vote.id) AS vote'),
            'joins' => array(
                array(
                    'table' => 'entries',
                    'alias' => 'Entry',
                    'type' => 'INNER',
                    'conditions' => array('EntryMeta.entry_id = Entry.id')
                ),
                array(
                    'table' => 'user_metas',
                    'alias' => 'UserMeta',
                    'type' => 'INNER',
                    'conditions' => array('UserMeta.user_id = Entry.user_id AND UserMeta.meta_key = "full_name"')
                ),
                array(
                    'table' => 'images',
                    'alias' => 'Image',
                    'type' => 'INNER',
                    'conditions' => array('EntryMeta.meta_key = "image_id" AND EntryMeta.meta_value = Image.id')
                ),
                array(
                    'table' => 'votes',
                    'alias' => 'Vote',
                    'type' => 'LEFT',
                    'conditions' => array('Entry.id = Vote.entry_id')
                ),
            ),
            'group' => 'Entry.id',
            'limit' => 15,
            'order' => array('vote'=>'DESC'),
        )
    );
    
    protected $yt;
    protected $yt_config = array(
        'username' => 'kroneiik777@gmail.com',
        'password' => '11300601',
        'slug' => 'NxTube',
        'devkey' => 'AI39si52wJ-yiy4oc3nqGoIGSgTMFoVdalXDXn9MS5N9pQXtHJGLYqszXkcx4RUw97O2rntesMvJausODvXN8B60pDR8PB3BUQ',
        'title' => 'Jobstreet I Found Mine Contest 2011',
        'desc' => 'Jobstreet I Found Mine Contest 2011 - join now!',
        'ctgy' => 'Entertainment',
        'tag' => 'jobstreet, contest, fun',
    );
    
	function index() {
        if ($this->Session->read('Auth.User')) {
            $this->Entry->recursive = 0;
            $this->set('entries', $this->paginate());
        }else{
            $this->redirect(array('action'=>'gallery'));    
        }
		//$this->Entry->recursive = 0;
		//$this->set('entries', $this->paginate());
	}

	function view($id = null) {
        $this->layout = 'ajax';
        if (!$id) {
			$this->Session->setFlash(__('Invalid entry', true));
			$this->redirect(array('action' => 'index'));
		}
        $this->Entry->unbindModel(array('hasMany'=>array('EntryMeta')));
        $this->Entry->bindModel(array('hasOne'=> array(
            'EntryMeta',
            'Image' => array(
                'className' => 'Image',
                'foreignKey' => false,
                'conditions' => array('Image.id = EntryMeta.meta_value AND EntryMeta.meta_key = "image_id"')
            )
        )));
        //$record = $this->Entry->read('Image.img_file', $id);
        //$this->_viewImg($record['Image']['img_file']);
        //$this->requestAction(array('controller'=>'images', 'action'=>'img', $record['Image']['img_file']));
        //$this->Images->img($record['Image']['img_file']);
        //debug($record);
        $this->set('entry', $this->Entry->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Entry->create();
			if ($this->Entry->save($this->data)) {
				$this->Session->setFlash(__('The entry has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The entry could not be saved. Please, try again.', true));
			}
		}
		$users = $this->Entry->User->find('list');
		$this->set(compact('users'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid entry', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Entry->save($this->data)) {
				$this->Session->setFlash(__('The entry has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The entry could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Entry->read(null, $id);
		}
		$users = $this->Entry->User->find('list');
		$this->set(compact('users'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for entry', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Entry->delete($id)) {
			$this->Session->setFlash(__('Entry deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Entry was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
    
    function upload(){
        $this->layout = 'ajax';
        
        if (!empty($this->data)) {
            //echo '<pre>'.print_r($this,1).'</pre>';
            $this->data['Entry']['datecreated'] = date('Y-m-d H:i:s');   //0000-00-00 00:00:00
            
            $this->Entry->EntryMeta->Image->set($this->data);
            if($this->Entry->EntryMeta->Image->validates()){
                $this->Entry->EntryMeta->Image->save($this->data, false);
                $this->Entry->save($this->data);
                $this->data['EntryMeta'] = array(
                    'entry_id' => $this->Entry->id,
                    'meta_key' => 'image_id',
                    'meta_value' => $this->Entry->EntryMeta->Image->id,
                    'lastupdatetime' => date('Y-m-d H:i:s'),
                    'lastupdateuser' => $this->Session->read('User.uid'),
                );
                if($this->Entry->EntryMeta->save($this->data)){
                    $this->Session->setFlash("Photo submitted!");
                    if(isset($this->data['Image']['publish']))
                        $this->Session->write('Image.publish', 1);
                    $this->redirect(sprintf('/entries/uploaded/%d/%d', $this->Entry->id, $this->Entry->EntryMeta->Image->id));
                }   
            }else{
                $this->Session->setFlash("Gotcha! Fix it");
                $errors = $this->Entry->EntryMeta->Image->invalidFields(); // contains validationErrors array
            }
        } else {
            if(!$this->Session->check('User.uid'))
                $this->redirect(array('controller'=>'users', 'action'=>'register'));
            if(!isset($this->data['Entry']['user_id']))
                $this->data['Entry']['user_id'] = $this->Session->read('User.uid');
            $this->_ytInsert();
        }
    }
    
    function uploaded($eid, $img){
        $img_data = $this->Entry->EntryMeta->Image->read(null, $img);
        $data = array(
            'link' => substr($this->_fb_config['canvas'], 0, -1).Router::url(
                array('controller' => 'entries', 'action' => 'vote', $eid, 'base' => false)),
            /*'picture' => Router::url(
                array('controller' => 'entries', 'action' => 'thumb', $eid), true),*/
            'picture' => Router::url('/img/uploads/images/thumb/small/'.$img_data['Image']['img_file'], true),
            'description' => $img_data['Image']['name'],
            'appid' => $this->facebook->getAppId(),
        );
        $this->set('data', $data);
        if($this->Session->read('Image.publish')){
            $tmp = $this->Entry->User->UserMeta->find('first', array(
                'fields' => 'user_id',
                'conditions' => array(
                    'meta_key' => 'fbid',
                    'meta_value' => $this->Session->read('User.fbid')
                )
            ));
            $album = $this->Entry->User->UserMeta->find('first', array(
                'fields' => 'meta_value',
                'conditions' => array(
                    'user_id' => $tmp['UserMeta']['user_id'],
                    'meta_key' => 'album_id',
                )
            ));
            if(!$album){
                $album_id = $this->facebook->api('/me/albums', 'post', array(       //Create Album on FB
                    'name'=> $this->_fb_config['name'],          //Album Name
                    //'message'=>$this->_fb_config['canvas'],     //Album Description
                    'message'=> $this->_fb_config['caption'].' '.$this->_fb_config['canvas'],
                ));
                if($album_id){
                    $this->Entry->User->UserMeta->create();
                    $data = array(
                        'user_id' => $tmp['UserMeta']['user_id'],
                        'meta_key' => 'album_id',
                        'meta_value' => $album_id['id'],
                        'lastupdatetime' => date('Y-m-d H:i:s'),
                    );
                    if($this->Entry->User->UserMeta->save($data)){
                        //$this->Session->setFlash('An album is created on your Facebook.');                    
                    }
                    $aid = $album_id['id'];
                }
            }else{
                $aid = $album['UserMeta']['meta_value'];
            }
            if($aid>0){
                if($img_data['Image']['name']!='') $message = sprintf("*%s* ", $img_data['Image']['name']);
                $message .= $this->_fb_config['message'];
                $message .= substr($this->_fb_config['canvas'], 0, -1).Router::url(
                    array('controller' => 'entries', 'action' => 'vote', $eid, 'base' => false));
                $this->facebook->setFileUploadSupport(true);
                $this->facebook->api("/$aid/photos", 'post', array(
                    //'source' => Router::url('/img/uploads/images/'.$img_data['Image']['img_file'], true),
                    'source' => '@' . IMAGES. 'uploads' . DS . 'images' . DS . $img_data['Image']['img_file'],
                    'message' => $message,
                ));
            }
        }
    }
    
    function thumb($id = null) {
        if (!$id) {
			$this->Session->setFlash(__('Invalid image', true));
			$this->redirect(array('action' => 'index'));
		}
        $this->Entry->EntryMeta->read('Image.img_file', $id);
        $record = array_shift($this->Entry->EntryMeta->data);
        if($record['img_file']) $this->_viewImg($record['img_file'], true);
    }
    
    function gallery(){
        //$this->Entry->recursive = 0;
        //$this->Entry->unbindModel(array('hasMany'=>array('EntryMeta')));
        /*$this->Entry->EntryMeta->bindModel(array('hasOne'=> array(
            'UserMeta' => array(
                'className' => 'UserMeta',
                'foreignKey' => false,
                'conditions' => array('UserMeta.user_id = Entry.user_id AND UserMeta.meta_key = "full_name"'),
                'type' => 'INNER'
            )
        )));
        $options['joins'] = array(
            array(
                'table' => 'user_metas',
                'alias' => 'UserMeta',
                'type' => 'INNER',
                'conditions' => array(
                    'UserMeta.user_id = Entry.user_id AND UserMeta.meta_key = "full_name"'
                )
            )
        ); */
        
        //$record = $this->Entry->read(null, $id);
        //debug($record);
		//$this->set('images', $this->paginate('Entry'));
        
        $this->Entry->EntryMeta->recursive = -1;
        $this->Entry->EntryMeta->unbindModel(array('belongsTo'=>array('Entry')));
        /*$this->Entry->EntryMeta->bindModel(array('belongsTo' => array(
            'Entry' => array(
                'className' => 'Entry',
                'foreignKey' => 'entry_id',
                'type' => 'INNER', 
            ),
            'UserMeta' => array(
                'className' => 'UserMeta',
                'foreignKey' => false,
                'type' => 'INNER',
                'conditions' => array('UserMeta.user_id = Entry.user_id AND UserMeta.meta_key = "full_name"')
            ),
            'Image' => array(
                'className' => 'Image',
                'foreignKey' => 'meta_value',
                'conditions' => 'EntryMeta.meta_key="image_id"',
                'type' => 'INNER'
            ), 
        )));*/
        //$result = $this->Entry->EntryMeta->find('all');
        //debug($result);
        /*$options['joins'] = array(
            array(
                'table' => 'user_metas',
                'alias' => 'UserMeta',
                'type' => 'INNER',
                'conditions' => array('UserMeta.user_id = Entry.user_id AND UserMeta.meta_key = "full_name"')
            )
        ); */
		//$this->set('images', $this->paginate('Entry.EntryMeta', $options));
        $this->set('images', $this->paginate('Entry.EntryMeta'));
        $this->set('fb', $this->_fb_config);
    }
    
    function vote($id=null){
        if($this->RequestHandler->isAjax()){
            if(empty($id)){
                $this->Session->setFlash(__('Invalid vote', true));
            }else{
                if(!$this->Session->read('User.fbid') && $this->params['named']['uid']) $this->Session->write('User.fbid', $this->params['named']['uid']);
                $data['Vote'] = array(
                    'timestamp' => date('Y-m-d H:i:s'),   //0000-00-00 00:00:00
                    'trackip' => $_SERVER['REMOTE_ADDR'],
                    'user_id' => $this->Session->read('User.fbid'),
                    'entry_id' => $id,
                );
                $this->Entry->Vote->create();
                if($this->Entry->Vote->save($data)){
                    $this->Session->setFlash(__('Thanks for your support! You may vote for my photo again tomorrow :)', true));
                    if(isset($this->params['named']['post']) && $this->params['named']['post']==1){
                        $this->Entry->EntryMeta->recursive = -1;
                        $this->Entry->EntryMeta->unbindModel(array('belongsTo'=>array('Entry')));
                        $args = array_merge($this->paginate['EntryMeta'], array('conditions'=>array('Entry.id'=>$id)));
                        $data = $this->Entry->EntryMeta->find('first', $args);
                        $post = array(
                            'message' => 'I\'ve just voted for this pic in the '.$this->_fb_config['name'].' contest!',
                            'name' => $this->_fb_config['name'],
                            'link' => $this->_fb_config['canvas'].'entries/vote/'.$id,
                            'description' => 'caption: '.$data['Image']['name'],
                            'picture' => Router::url('/img/uploads/images/thumb/small/'.$data['Image']['img_file'], true),
                            'caption' => $this->_fb_config['caption'],
                        );
                        $this->facebook->api('/me/feed', 'post', $post);
                    }
                }else{
                    //debug($this->Entry->Vote->invalidFields());
                    $this->Session->setFlash(__(array_shift($this->Entry->Vote->invalidFields()), true));
                }
            }
        }else{
            if(empty($id)){
                $this->Session->setFlash(__('Photo is not exists. Please choose another one', true));
                $this->redirect(array('controller'=>'entries', 'action'=>'gallery'));
            }else{
                $this->Entry->EntryMeta->recursive = -1;
                $this->Entry->EntryMeta->unbindModel(array('belongsTo'=>array('Entry')));
                $args = array_merge($this->paginate['EntryMeta'], array('conditions'=>array('Entry.id'=>$id)));
                $this->set('data', $this->Entry->EntryMeta->find('first', $args));
                if(!$this->Session->read('User.fbid')){ //User havent grant permission
                    $this->set('login', $this->facebook->getLoginUrl(array(
                        'scope'=>$this->_fb_config['perms'],
                        'redirect_uri'=>$this->_fb_config['canvas'].'entries/vote/'.$id,
                    )));
                }
            }
        }
        
        /*
        if (empty($this->data)) {
			$this->Session->setFlash(__('Invalid vote', true));
			$this->redirect(array('action' => 'gallery'));
		} else {
            $this->data['Vote']['timestamp'] = date('Y-m-d H:i:s');   //0000-00-00 00:00:00
            $this->data['Vote']['trackip'] = $_SERVER["REMOTE_ADDR"];
            $this->data['Vote']['user_id'] = $this->Session->read('User.uid');
            $this->Entry->Vote->create();
			if ($this->Entry->Vote->save($this->data)) {
				$this->Session->setFlash(__('Thanks for your vote', true));
				$this->redirect(array('action' => 'view', $this->data['Vote']['entry_id']));
			} else {
                //$this->Session->setFlash(__(array_shift, true));
                echo $this->Entry->invalidFields();
                exit();
                //$this->Session->setFlash(__('The vote could not be saved. Please, try again.', true));
                $this->redirect(array('action' => 'view', $this->data['Vote']['entry_id']));
			}
		}*/
    }
    
    function beforeFilter() {
        parent::beforeFilter(); 
        //add permission exclusion
        //$this->Auth->allow(array('upload','uploaded','gallery','view','vote','thumb','callback'));
        $this->Auth->allow('*');
        if($this->Auth->user('role') != 'admin'){
            $this->Auth->deny('add', 'delete', 'edit');   
        }
        
        //load youtube framework
        $this->_ytInit();
    }
    
     /** 
     * displays and resizes an image 
     * @width   = width to resize image to 
     * @height  = height to resize image to 
     * @resize  = true/false 
     * @src     = src dir of image from root 
     */  
    function _viewImg($url, $thumb=false) {  
        $full_path = IMAGES . 'uploads' . DS . 'images' . DS;
        if($thumb) $full_path .= 'thumb' . DS . 'small' . DS;
        $full_path .= $url;  
        $size   = getimagesize($full_path);
        $mime   = $size['mime'];
        $data = file_get_contents($full_path);  
        $this->RequestHandler->respondAs($mime);  
        //header('Content-Length: ' . strlen($data));  
        echo $data;  
        return $this->_stop();
    }
    
    function _ytInit(){
        App::import('Vendor', 'Zend/Loader');
        Zend_Loader::loadClass('Zend_Gdata_YouTube');
        Zend_Loader::loadClass('Zend_Gdata_ClientLogin'); 
        
        $authenticationURL = 'https://www.google.com/accounts/ClientLogin';
        
        $httpClient = Zend_Gdata_ClientLogin::getHttpClient(
                      $username = $this->yt_config['username'],
                      $password = $this->yt_config['password'],
                      $service = 'youtube',
                      $client = null,
                      $source = $this->yt_config['slug'], // a short string identifying your application
                      $loginToken = null,
                      $loginCaptcha = null,
                      $authenticationURL);
        
        $developerKey = $this->yt_config['devkey'];
        $applicationId = $this->yt_config['slug'];
        $clientId = $this->yt_config['slug'];
        
        $this->yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);
    }
    
    function _ytInsert(){
        // create a new VideoEntry object
        $myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
        
        $myVideoEntry->setVideoTitle($this->yt_config['title']);
        $myVideoEntry->setVideoDescription($this->yt_config['desc']);
        // The category must be a valid YouTube category!
        $myVideoEntry->setVideoCategory($this->yt_config['ctgy']);
        
        // Set keywords. Please note that this must be a comma-separated string
        // and that individual keywords cannot contain whitespace
        $myVideoEntry->SetVideoTags($this->yt_config['tag']);
        
        $tokenHandlerUrl = 'http://gdata.youtube.com/action/GetUploadToken';
        $tokenArray = $this->yt->getFormUploadToken($myVideoEntry, $tokenHandlerUrl);
        $this->set('yt_token', $tokenArray['token']);
        $this->set('yt_post', $tokenArray['url'] . '?nexturl=' . Router::url(array('controller' => 'entries', 'action' => 'callback'), true));
    }
    
    function callback(){
        $this->layout = 'ajax';
        
        $this->set('param', $this->params['url']);
    }
}

