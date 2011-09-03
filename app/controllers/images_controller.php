<?php
class ImagesController extends AppController {

	var $name = 'Images';

	function index() {
		$this->Image->recursive = 0;
		$this->set('images', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid image', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('image', $this->Image->read(null, $id));
	}
    
    function thumb($id = null) {
        if (!$id) {
			$this->Session->setFlash(__('Invalid image', true));
			$this->redirect(array('action' => 'index'));
		}
        $this->Image->read('img_file', $id);
        if (!empty($this->params['requested'])) {
            //$thumb = IMAGES . 'uploads' . DS . 'images' . DS . 'thumb' . DS . 'small' . DS;
            $thumb = Router::url('/img/uploads/images/thumb/small/', true);
            $record = array_shift($this->Image->data);
            $thumb .= $record['img_file']; 
            $this->redirect($thumb);
            //return $thumb;
        }else{
            $record = $this->Image->data;
            $this->set(compact('record'));
        }
    }

	function add() {
		if (!empty($this->data)) {
			$this->Image->create();
			if ($this->Image->save($this->data)) {
				$this->Session->setFlash(__('The image has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The image could not be saved. Please, try again.', true));
			}
		}
		$galleries = $this->Image->Gallery->find('list');
		$this->set(compact('galleries'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid image', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Image->save($this->data)) {
				$this->Session->setFlash(__('The image has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The image could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Image->read(null, $id);
		}
		$galleries = $this->Image->Gallery->find('list');
		$this->set(compact('galleries'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for image', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Image->delete($id)) {
			$this->Session->setFlash(__('Image deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Image was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
    
    function beforeFilter() {
        parent::beforeFilter(); 
        $this->Auth->allow(array('thumb','img'));
    }
    
     /** 
     * displays and resizes an image 
     * @width   = width to resize image to 
     * @height  = height to resize image to 
     * @resize  = true/false 
     * @src     = src dir of image from root 
     */  
    function img($url) {  
        // get params
  
        // get full image path  
        $full_path = IMAGES . 'uploads' . DS . 'images' . DS .$url;  
  
        // check file exists  
        /*if(file_exists($full_path)) {  
            // get size of image  
            $size   = getimagesize($full_path);  
            // get mimetype  
            $mime   = $size['mime'];  
 
            // if either width or height is an asterix  
            if($width == '*' || $height == '*') {  
                if($height == '*') {  
                    // recalculate height  
                    $height = ceil($width / ($size[0]/$size[1]));  
                } else {  
                    // recalculate width  
                    $width = ceil(($size[0]/$size[1]) * $height);  
                }  
            } else {  
                if (($size[1]/$height) > ($size[0]/$width)) {  
                    $width = ceil(($size[0]/$size[1]) * $height);  
                } else {  
                    $height = ceil($width / ($size[0]/$size[1]));  
                }  
            }  
  
            // include folder in filename  
            $dir_path = preg_replace("/[^a-z0-9_]/", "_", strtolower(dirname($url)));  
            $dir_path .= '-'.basename($url);  
      
            // create new file names  
            $file_relative = $this->cache_dir.'/'.$width.'x'.$height.'_'.$dir_path;  
            $file_cached = WWW_ROOT.$this->cache_dir.DS.$width.'x'.$height.'_'.$dir_path;  
      
            // if cached file already exists  
            if(file_exists($file_cached)) {  
                // get image sizes  
                $csize = getimagesize($file_cached);  
                // check that cached file is correct dimensions  
                $cached = ($csize[0] == $width && $csize[1] == $height);  
                // check file age  
                if (@filemtime($cachefile) < @filemtime($url))  
                    $cached = false;  
            } else {  
                $cached = false;  
            }  
  
            // if file not cached  
            if(!$cached) {  
                $resize = ($size[0] > $width || $size[1] > $height) || ($size[0] < $width || $size[1] < $height);  
            } else {  
                $resize = false;  
            }  
      
  
            // do not resize if set to true  
            if($noresize == 'true') {  
                $resize = false;  
                $cached = false;  
            }  
      
            // if image resize is necessary  
            if($resize) {  
                // image  
                $image = call_user_func('imagecreatefrom'.$this->types[$size[2]], $full_path);  
                if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor ($width, $height))) {  
                    imagecopyresampled ($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);  
                } else {  
                    $temp = imagecreate($width, $height);  
                    imagecopyresized($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);  
                }  
                call_user_func("image".$this->types[$size[2]], $temp, $file_cached);  
                imagedestroy($image);  
                imagedestroy($temp);  
            } elseif(!$cached) {  
                // copy original file  
                copy($full_path, $file_cached);  
            }  
  
            // get file contents  
            $data   = file_get_contents($file_cached);        
        } else {  */
            $size   = getimagesize($full_path);  
            $mime   = $size['mime'];  
            $data = file_get_contents($full_path);  
        //}  
  
        // set headers and output image
        $this->RequestHandler->respondAs($mime);  
        //header("Content-type: $mime");  
        header('Content-Length: ' . strlen($data));  
        echo $data;  
        return $this->_stop();
    }
}
?>