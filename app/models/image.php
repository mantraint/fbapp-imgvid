<?php
class Image extends AppModel {
	var $name = 'Image';
	var $validate = array(
		'gallery_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		/*'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please describe your photo',
				'allowEmpty' => false,
				//'required' => false,
				'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),*/
		'img_file' => array(
            'notEmpty' => array(
                'rule' => array('isUploadedFile'),
                'message' => 'Please select the photo you wish to upload',
            ),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Gallery' => array(
			'className' => 'Gallery',
			'foreignKey' => 'gallery_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
            'type' => 'INNER',
		)
	);
	
    var $actsAs = array(
        'MeioUpload' => array(
    		'img_file' => array(
                'dir' => 'img{DS}uploads{DS}images',
                'create_directory' => true,
                'allowed_mime' => array('image/jpeg', 'image/pjpeg', 'image/png'),
                'allowed_ext' => array('.jpg', '.jpeg', '.png'),
    			'zoomCrop' => true,
                'thumbsizes' => array(
                    'normal' => array('width' => 600, 'height' => 600),
                    'small'  => array('width' => 200, 'height' => 150),
                ),
    			'default' => 'default.jpg'
            )
        )
    );
    
    // Based on comment 8 from: http://bakery.cakephp.org/articles/view/improved-advance-validation-with-parameters
    function isUploadedFile($params){
    	$val = array_shift($params);
    	if ((isset($val['error']) && $val['error'] == 0) ||
    	(!empty( $val['tmp_name']) && $val['tmp_name'] != 'none')) {
    		return is_uploaded_file($val['tmp_name']);
    	}
    	return false;
    }

}
?>