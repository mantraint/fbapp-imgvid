<?php
class Vote extends AppModel {
	var $name = 'Vote';
    var $components = array('Session');
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Entry' => array(
			'className' => 'Entry',
			'foreignKey' => 'entry_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
            'type' => 'INNER'
		),
		/*'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
            'type' => 'INNER'
		)*/
	);
    
    var $validate = array(
        'entry_id' => array(
            'voteDaily' => array(
                'rule' => array('checkVote', 1),    //1 vote per day
                'message' => 'You have already voted for this photo today. You may only vote for an entry once every calendar day. Please come back again tomorrow. Thank you!',
            )
        ),
        'user_id' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'You need to grant access to the application in order to vote!'
        )
    );
    
    function checkVote($data, $limit){
        $record = $this->find('count', array(
            'conditions' => array(
                'Vote.entry_id' => array_shift($data),
                'Vote.user_id' => CakeSession::read('User.fbid'),
                'Vote.timestamp >= CURDATE()'
            ),
            'type' => 'INNER',
            'recursive' => -1,
        ));
        //echo $record;
        //exit();
        return $record < $limit;
    }
}
