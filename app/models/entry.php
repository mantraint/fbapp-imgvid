<?php
class Entry extends AppModel {
	var $name = 'Entry';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
            'type' => 'INNER'
		)
	);

	var $hasMany = array(
		'EntryMeta' => array(
			'className' => 'EntryMeta',
			'foreignKey' => 'entry_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Vote' => array(
			'className' => 'Vote',
			'foreignKey' => 'entry_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

    var $validate = array(
        'media' => array(
            'rule1' => array(
                'rule' => array('isUploadedFile'),
                'message' => 'This is not a valid file. Try again',
            ),
            'ru1e2' => array(
                'rule' => array('extension', array('gif', 'jpeg', 'png', 'jpg')),
                'message' => 'This photo format is wrong (.gif/.jpeg/.png/.jpg)'
            ),
        ),
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

class EntryMeta extends AppModel {
	var $name = 'EntryMeta';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Entry' => array(
			'className' => 'Entry',
			'foreignKey' => 'entry_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
            'type' => 'INNER',
		),
        'Image' => array(
            'className' => 'Image',
            'foreignKey' => 'meta_value',
            'conditions' => 'EntryMeta.meta_key="image_id"',
            'type' => 'INNER'
        ),
        'Video' => array(
            'className' => 'Video',
            'foreignKey' => 'meta_value',
            'conditions' => 'EntryMeta.meta_key="video_id"',
            'type' => 'INNER'
        ),
	);
    
    /**
     * Overridden paginateCount method
     */
    function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
        $conditions = array(
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
            ),
        );
    	//$sql = "SELECT DISTINCT ON(week, home_team_id, away_team_id) week, home_team_id, away_team_id FROM games";
    	$this->recursive = $recursive;
        if(is_array($conditions) && isset($conditions['group'])) unset($conditions['group']);
        $count = $this->find('count', $conditions);
        //debug($count);
    	//$results = $this->query($sql);
    	return $count;
    }
}