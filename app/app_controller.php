<?php
App::import('Vendor', 'facebook/facebook');

class AppController extends Controller {
    var $components = array('Acl', 'Auth', 'Session', 'RequestHandler');
    var $helpers = array('Html', 'Form', 'Session', 'Ajax', 'Js' => array('Prototype'));
    
    var $facebook;  
    
    protected $_fb_config = array(
        'appId'  => '207531312600207',
        'secret' => '03d009f99135cc7e92f95edd683d803a',
        'cookie' => true,
        'canvas' => 'http://localhost/sandbox/',
        'perms'  => 'user_birthday,user_about_me,user_likes,email,publish_stream',
        'page'   => '128556417157931', 
        'exclude'=> array('splash', 'vote'),                 //page action to exclude fb permission
        'name'   => 'MyEG Eason Chan Tix Giveaway',
        'caption'=> 'Win FREE VVIP* tix to catch Eason Chan live in concert!',
        'message'=> 'I\'m taking part in the MyEG Eason Chan Tix Giveaway contest! Vote for me now and help me walk away with a pair of VIP/VVIP tix to see Eason Chan live in concert!',
    );
    
    var $fbid;
    
    function beforeFilter() {
        //Configure AuthComponent
        $this->Auth->authorize = 'actions';
        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
        $this->Auth->logoutRedirect = array('controller' => 'entries', 'action' => 'index');
        $this->Auth->loginRedirect = array('controller' => 'entries', 'action' => 'index');
        
        $this->Auth->actionPath = 'controllers/';
        $this->Auth->allowedActions = array('display', 'inviteFriends', 'publishWall', 'splash');
        
        $this->_fbInit();
    }
    
    function inviteFriends(){
        $this->layout = 'ajax';
        $data = $this->facebook->api('/me');
        if(isset($data['first_name'])){
            $name = $data['first_name'];
        }
        $args = array(
            'app_id' => $this->_fb_config['appId'],
            'display' => 'iframe',
            'access_token' => $this->facebook->getAccessToken(),
            //'redirect_uri' => Router::url('/entries/gallery', true),
            'redirect_uri' => Router::url('/pages/close', true),
            'message' => $name.' invites you to join MyEG Eason Chan Tix Giveaway contest for a chance to walk away with a pair of VIP/VVIP tix to see Eason Chan live in concert!',
            'title' => $this->_fb_config['name'],
        );
        $args = array_merge($args, $this->passedArgs);
        $this->_fbDialog($args, 'apprequests');
    }
    
    function publishWall(){
        $args = array(
            'app_id' => $this->_fb_config['appId'],
            'display' => 'iframe',
            'access_token' => $this->facebook->getAccessToken(),
            'link' => $this->_fb_config['canvas'],
            'picture' => '',
            'name' => $this->_fb_config['name'],
            'caption' => $this->_fb_config['caption'],
            //'description' => 'Dummy Text 3',
            'redirect_uri' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
            'message' => $this->_fb_config['message'],
        );
        $args = array_merge($args, $this->passedArgs);
        $this->_fbDialog($args);
    }
    
    function _fbDialog($params, $method='feed'){
        if(isset($params['para'])) $params['redirect_uri'] .= '/para:' . $params['para'];
        $link = "https://www.facebook.com/dialog/$method?" . http_build_query( $params );
        $this->redirect("https://www.facebook.com/dialog/$method?" . http_build_query( $params ) );
    }
    
    function _fbInit(){
        // Prevent the 'Undefined index: facebook_config' notice from being thrown.  
        $GLOBALS['facebook_config']['debug'] = NULL;  
        // Create a Facebook client API object.  
        $this->facebook = new Facebook($this->_fb_config);
        $this->set('fb_config', $this->_fb_config);
        $this->set('fb_appid', $this->facebook->getAppId());
        
        $fbid = $this->facebook->getUser();
        try {
            $data = $this->facebook->getSignedRequest();        //on FB Page
            $liked = false;
            if(isset($data['page'])) 
                $liked = $data['page']['liked'];
            else{
                // Proceed knowing you have a logged in user who's authenticated.
                $data = $this->facebook->api('/me/likes');
                $data = array_shift($data);
                //echo '<pre>'.print_r($data,1).'</pre>';
                if(is_array($data)){
                    foreach($data as $d){
                        if(isset($d['id']) && $d['id']==$this->_fb_config['page']){
                            $liked = true;
                            break;
                        }
                    }
                }
            }
            $this->set('liked', $liked);
        } catch (FacebookApiException $e) {
            error_log($e);
            $fbid = null;
        }
        $this->fbid = $fbid;
        if(!$this->fbid && !in_array($this->params['action'], $this->_fb_config['exclude']) && !$this->RequestHandler->isAjax()){
            echo "<script>top.location.href='".$this->facebook->getLoginUrl(array('redirect_uri'=> $this->_fb_config['canvas'], 'scope'=> $this->_fb_config['perms']))."'</script>";
            return $this->_stop();
        }else{
            $this->Session->write('User.fbid', $this->fbid);
        }
    }
    
    function _isUrl($url){
        $urlregex = "/^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$/";
        return preg_match($urlregex, $url);
    }
    
	function build_acl() {
		if (!Configure::read('debug')) {
			return $this->_stop();
		}
		$log = array();

		$aco =& $this->Acl->Aco;
		$root = $aco->node('controllers');
		if (!$root) {
			$aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
			$root = $aco->save();
			$root['Aco']['id'] = $aco->id; 
			$log[] = 'Created Aco node for controllers';
		} else {
			$root = $root[0];
		}   

		App::import('Core', 'File');
		$Controllers = App::objects('controller');
		$appIndex = array_search('App', $Controllers);
		if ($appIndex !== false ) {
			unset($Controllers[$appIndex]);
		}
		$baseMethods = get_class_methods('Controller');
		$baseMethods[] = 'build_acl';

		$Plugins = $this->_getPluginControllerNames();
		$Controllers = array_merge($Controllers, $Plugins);

		// look at each controller in app/controllers
		foreach ($Controllers as $ctrlName) {
			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

			// Do all Plugins First
			if ($this->_isPlugin($ctrlName)){
				$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
				if (!$pluginNode) {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
					$pluginNode = $aco->save();
					$pluginNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
				}
			}
			// find / make controller node
			$controllerNode = $aco->node('controllers/'.$ctrlName);
			if (!$controllerNode) {
				if ($this->_isPlugin($ctrlName)){
					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
					$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
				} else {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $ctrlName;
				}
			} else {
				$controllerNode = $controllerNode[0];
			}

			//clean the methods. to remove those in Controller and private actions.
			foreach ($methods as $k => $method) {
				if (strpos($method, '_', 0) === 0) {
					unset($methods[$k]);
					continue;
				}
				if (in_array($method, $baseMethods)) {
					unset($methods[$k]);
					continue;
				}
				$methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
				if (!$methodNode) {
					$aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
					$methodNode = $aco->save();
					$log[] = 'Created Aco node for '. $method;
				}
			}
		}
		if(count($log)>0) {
			debug($log);
		}
	}

	function _getClassMethods($ctrlName = null) {
		App::import('Controller', $ctrlName);
		if (strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num+1);
		}
		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);

		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars($ctrlclass);
		if (array_key_exists('scaffold',$properties)) {
			if($properties['scaffold'] == 'admin') {
				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
			} else {
				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
			}
		}
		return $methods;
	}

	function _isPlugin($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) > 1) {
			return true;
		} else {
			return false;
		}
	}

	function _getPluginControllerPath($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		} else {
			return $arr[0];
		}
	}

	function _getPluginName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0];
		} else {
			return false;
		}
	}

	function _getPluginControllerName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[1];
		} else {
			return false;
		}
	}
    
    function splash(){
        $this->layout = 'tab';
    }

/**
 * Get the names of the plugin controllers ...
 * 
 * This function will get an array of the plugin controller names, and
 * also makes sure the controllers are available for us to get the 
 * method names by doing an App::import for each plugin controller.
 *
 * @return array of plugin names.
 *
 */
	function _getPluginControllerNames() {
		App::import('Core', 'File', 'Folder');
		$paths = Configure::getInstance();
		$folder =& new Folder();
		$folder->cd(APP . 'plugins');

		// Get the list of plugins
		$Plugins = $folder->read();
		$Plugins = $Plugins[0];
		$arr = array();

		// Loop through the plugins
		foreach($Plugins as $pluginName) {
			// Change directory to the plugin
			$didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = $folder->findRecursive('.*_controller\.php');

			// Loop through the controllers we found in the plugins directory
			foreach($files as $fileName) {
				// Get the base file name
				$file = basename($fileName);

				// Get the controller name
				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
					if (!App::import('Controller', $pluginName.'.'.$file)) {
						debug('Error importing '.$file.' for plugin '.$pluginName);
					} else {
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
					}
				}
			}
		}
		return $arr;
	}

}
?>
