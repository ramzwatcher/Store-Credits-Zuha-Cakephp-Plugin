<?php
class Credit extends AppModel {
	var $name = 'Credit';
	var $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'CreditType' => array(
			'className' => 'Enumeration',
			'foreignKey' => 'credit_type_id',
			'conditions' => array('CreditType.type' => 'CREDIT_TYPE'),
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Creator' => array(
			'className' => 'Users.User',
			'foreignKey' => 'creator_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Modifier' => array(
			'className' => 'Users.User',
			'foreignKey' => 'modifier_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	
	function add($data) {
		if (empty($data['Credit']['user_id']) && isset($data['Credit']['email'])) {
			$userCredit = $this->User->find('first' , array(
							'conditions' => array('User.email' => $data['Credit']['email'])
			));
		} else {
			$userCredit = $this->User->find('first' , array(
							'conditions' => array('User.id' => $data['Credit']['user_id'])
			));
		}
		$userCredit['User']['credit_total'] +=  $data['Credit']['value'];		

		// we should not mess with other stuff, hence save only
		if ($this->User->save($userCredit, false)) {
			return true;
		} else {
			return false;
		}
	}

	/*
	 * 
	 * 
	 */
	function changeUserCredits($data) {

		$creditData = $this->User->find('first' , array('conditions' => 
											array('User.id' => $data['Credit']['user_id']))) ;
		$data['User']['credit_total'] = $creditData['User']['credit_total'] + $data['Credit']['quantity'] ; 
		if($this->User->save($data))
			return true ;
		else 
			return false ;
	}
}
?>s