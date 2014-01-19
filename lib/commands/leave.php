<?php

namespace OCA\Chat\Commands;

use \OCA\Chat\ChatAPI;
use \OCA\AppFramework\Core\API;
use \OCA\Chat\Db\User;
use \OCA\Chat\Db\UserMapper;
use \OCA\Chat\Db\Conversation;
use \OCA\Chat\Db\ConversationMapper;

class Leave extends ChatAPI {
	
	public function __construct(API $api){	
		parent::__construct($api);
	}

	public function setRequestData(array $requestData){
		$this->requestData = $requestData;
	}

	public function execute(){
		$userMapper = new UserMapper($this->api);
		$userMapper->deleteBySessionId($this->requestData['conv_id'], $this->requestData['session_id']);
		
		// Check if there are still users in this conversation
		// If there are no users in the conversation, delete the conversation 
		
		// fetch users in conversation by conversationid
		$users = $userMapper->findByConversation($this->requestData['conv_id']);
		// TODO check for multiple sessions by the same user
					\OCP\Util::writeLog('chat', json_encode($users), \OCP\Util::ERROR);

		if(count($users) <= 1){
			// there are no users in the conversatio -> conversation can be deleted
			$conversationMapper = new ConversationMapper($this->api);
			$conversationMapper->deleteConversation($this->requestData['conv_id']);
		
			foreach($users as $user){
									\OCP\Util::writeLog('chat', json_encode($user), \OCP\Util::ERROR);

				$userMapper->deleteBySessionId($user->getConversationId(), $user->getSessionId());
			}

		}		
		
	}	

}	