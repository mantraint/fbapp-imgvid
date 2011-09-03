<?php
class UsersController extends AppController {

	var $name = 'Users';
    var $helpers = array ('Html','Form');
    var $components = array('Session');
    
    const groupId = 3;
    
	function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
    
    function login() {
        //Auth Magic
        if ($this->Session->read('Auth.User')) {
    		$this->Session->setFlash('You are logged in!');
    		$this->redirect('/', null, false);
    	}
    }
     
    function logout() {
        $this->Session->setFlash('Good-Bye');
        $this->redirect($this->Auth->logout());
    }
    
    function register() {
        if(!($this->Session->read('User.fbid'))){
            $this->flash('You need to grant access to the application in order to join!', '', 5);
        }else{
            if($this->User->id){
                $this->Session->write('User.uid', $this->User->id);
                $this->redirect(array('controller' => 'entries', 'action' => 'upload'));
            }
            if (!empty($this->data)) {
                $this->User->set($this->data);
                //$this->User->create();
                if($this->User->validates()){
                    $this->data['User']['created'] = date('Y-m-d H:i:s');   //0000-00-00 00:00:00
                    $this->data['User']['group_id'] = self::groupId;        //Normal User
                    $this->data['User']['dob'] = $this->data['User']['dob']['year'].'-'.$this->data['User']['dob']['month'].'-'.$this->data['User']['dob']['day'];
                //}else{
                    //$error = $this->User->invalidFields();
                    //debug($error);
                /*$this->User->set(array(
                    'datecreated' => date('Y-m-d H:i:s'), //0000-00-00 00:00:00
                ));
                if ($this->User->save($this->data)) {
                    //$this->data['UserMeta']['user_id'] = $this->User->id;
                    foreach(@$this->data['UserMeta'] as $idx=>$usermeta){
                        //$this->User->UserMeta->create();
                        $this->data['UserMeta'][$idx]['user_id'] = $this->User->id;
                        $this->data['UserMeta'][$idx]['meta_value'] = $this->data['User'][$usermeta['meta_key']];
                        $this->data['UserMeta'][$idx]['lastupdateuser'] = $this->User->id;
                        $this->data['UserMeta'][$idx]['lastupdatetime'] = date('Y-m-d H:i:s');
                    }
                    //echo '<pre>'.print_r($this->data,1).'</pre>';
                    $this->User->UserMetas->saveAll($this->data['UserMeta']);
                    $this->Session->setFlash("Registration successful!");
                }*/
                    $this->_appendMeta();
                    foreach(@$this->data['UserMeta'] as $idx=>$usermeta){
                        //$this->User->UserMeta->create();
                        //$this->data['UserMeta'][$idx]['user_id'] = $this->User->id;
                        if(isset($this->data['User'][$usermeta['meta_key']]))
                            $this->data['UserMeta'][$idx]['meta_value'] = $this->data['User'][$usermeta['meta_key']];
                        //$this->data['UserMeta'][$idx]['lastupdateuser'] = $this->User->id;
                        $this->data['UserMeta'][$idx]['lastupdatetime'] = date('Y-m-d H:i:s');
                    }
                    
                    //unset($this->User->UserMeta->validate['user_id']);
                    if($this->User->saveAll($this->data, array('validate'=>false))){
                        $this->Session->setFlash("Registration successful!");
                        $this->Session->write('User.uid', $this->User->id);
                        $this->redirect(array('controller' => 'entries', 'action' => 'upload'));
                    }else{
                        $this->Session->setFlash("Your registration encountered error. Operation is cancelled.");
                    }
                }else{
                    //$this->Session->setFlash("Your details is incomplete. Please fill in the blank");
                }
            }else{
                $data = $this->_getFbProfile();
                //echo '<pre>'.print_r($data,1).'</pre>';
                if(preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $data['birthday'], $birthday)){
                    //$birthday = explode('/', $data['birthday']);
                    $this->data['User']['dob']['day'] = $birthday[2];
                    $this->data['User']['dob']['month'] = $birthday[1];
                    $this->data['User']['dob']['year'] = $birthday[3];
                }
                $this->data['User']['full_name'] = $data['name'];
                $this->data['User']['email'] = $data['email'];
                $this->data['User']['gender'] = substr($data['gender'], 0, 1);
                //$this->data['User']['dob'] = $data['birthday'];
                $this->data['User']['city'] = $data['hometown']['name'];
            }
            $states = array(
                'Perlis'=>'Perlis', 'Kedah'=>'Kedah', 'Kelantan'=>'Kelantan', 'Pulau Pinang'=>'Pulau Pinang', 'Terengganu'=>'Terengganu', 'Selangor'=>'Selangor', 'Johor'=>'Johor', 'Melaka'=>'Melaka', 'Pahang'=>'Pahang', 'Perak'=>'Perak', 'Wilayah Persekutuan'=>'Wilayah Persekutuan', 'Negeri Sembilan'=>'Negeri Sembilan', 'Sabah'=>'Sabah', 'Sarawak'=>'Sarawak', 'Labuan'=>'Labuan', 'Putrajaya'=>'Putrajaya'
            );
            $this->set('states', $states);
        }
	}
    
    function beforeFilter() {
        parent::beforeFilter(); 
        $this->Auth->allow(array('register'));
        // Get User ID
        $fbid = $this->_getFbId();
        //$this->set('fbid', $fbid);
        // We may or may not have this data based on whether the user is logged in.
        //
        // If we have a $user id here, it means we know the user is logged into
        // Facebook, but we don't know if the access token is valid. An access
        // token is invalid if the user logged out of Facebook.
        
        /*if ($fbid) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $this->facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $fbid = null;
            }
        }*/
        if($fbid){
            $result = $this->User->UserMeta->find('first', array(
                'fields' => array(
                    'User.id'
                ),
                'conditions' => array(
                    'UserMeta.meta_key' => 'fbid',
                    'UserMeta.meta_value' => $fbid,
                ),
            ));
            if($result && $result['User']['id']>0)
                $this->User->id = $result['User']['id'];
        }
    }
    
    function _getFbId(){
        //return $this->facebook->getUser();
        return $this->fbid;
    }
    
    function _getFbProfile(){
        //$id = $this->_getFbId();
        try {
            // Proceed knowing you have a logged in user who's authenticated.
            return $this->facebook->api('/me');
        } catch (FacebookApiException $e) {
            error_log($e);
        }
    }
    
    function _appendMeta(){
        $fbid = $this->_getFbId();
        if(!$fbid) return false;
        $data = array(
            'fbid' => $fbid,
        );
        $i = sizeof($this->data['UserMeta']);
        foreach($data as $k=>$v){
            $this->data['UserMeta'][$i]['meta_key'] = $k;
            $this->data['UserMeta'][$i]['meta_value'] = $v;
            $i++;
        }
    }
    
    function initDB() {
        $group =& $this->User->Group;
        //Allow admins to everything
        $group->id = 1;     
        $this->Acl->allow($group, 'controllers');
     
        //allow managers to posts and widgets
        $group->id = 2;
        $this->Acl->deny($group, 'controllers');
        $this->Acl->allow($group, 'controllers/Users');
        $this->Acl->allow($group, 'controllers/Entries');
     
        //allow users to only add and edit on posts and widgets
        $group->id = 3;
        $this->Acl->deny($group, 'controllers');        
        $this->Acl->allow($group, 'controllers/Entries/add');
        //$this->Acl->allow($group, 'controllers/Posts/edit');
        //we add an exit to avoid an ugly "missing views" error message
        echo "all done";
        exit;
    }

}
