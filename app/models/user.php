<?php
class User extends AppModel {
	var $name = 'User';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
    
    var $actsAs = array('Acl' => array('type' => 'requester'));

	var $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'Entry' => array(
			'className' => 'Entry',
			'foreignKey' => 'user_id',
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
		'UserMeta' => array(
			'className' => 'UserMeta',
			'foreignKey' => 'user_id',
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
			'foreignKey' => 'user_id',
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
    
    /*var $hasAndBelongsToMany = array(
        'Entry' => array(
            'className' => 'Entry',
            'joinTable' => 'entries_users',
			'foreignKey' => 'user_id',
            'associationForeignKey' => 'entry_id',
        )
    );*/
    
    var $validate = array(
        'full_name' => array(
            'rule' => 'notEmpty',
        ),
        'email' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'This field cannot be left blank',
                'last' => true,
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Invalid email, please enter a correct one',
            ),
        ),
        'phone' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'This field cannot be left blank',
                'last' => true,
            ),
            'phone' => array(
                'rule' => array('phone', '/^[\+]?[6]?0[1]?\d[\s\-]?[\d\s\-]{7,}$/', null),
                'message' => 'Invalid phone number, please enter a correct one',
            )
        ),
        'gender' => array(
            'rule' => 'notEmpty'
        ),
        'state' => array(
            'rule' => 'notEmpty'
        ),
        'city' => array(
            'rule' => 'notEmpty'
        ),
        'dob' => array(
            'notEmpty' => array(
                'rule' => array('checkBirthday'),
                'message' => 'Please fill in your birthday'
            )
        ),
        'agree' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'Official Contest Terms & Conditions must be accepted to proceed',
        ),
        'ic_no' => array(
            'rule' => 'notEmpty',
        ),
    );

    function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['group_id'])) {
    	$groupId = $this->data['User']['group_id'];
        } else {
        	$groupId = $this->field('group_id');
        }
        if (!$groupId) {
    	return null;
        } else {
            return array('Group' => array('id' => $groupId));
        }
    }
    
    function bindNode($user) {
        return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
    }
    
    function checkBirthday($data){
        $birthday = array_shift($data);
        if(!is_array($birthday)) return false;
        if(!($birthday['month']) || !($birthday['day']) || !($birthday['year'])) return false;
        return checkdate($birthday['month'], $birthday['day'], $birthday['year']);
    }
}

class UserMeta extends AppModel {
    var $name = 'UserMeta';
    
    var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
            'type' => 'INNER',
		)
	);
}
