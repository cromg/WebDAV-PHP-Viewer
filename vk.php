<?php
require_once "oauth.php";

class Vk extends oAuth{
	public $DOMAIN_MAP = array(
		'oauths' => 'https://api.vk.com/oauth/',
		'api'    => 'https://api.vk.com/method/'
	);
	public $AMETHOD    = 'authorize';
	public $APARAMS    = array(
		'scope' => 'groups'
	);
	public $TMETHOD    = 'access_token';
	
	public function getAudios($token, $uid = false, $page = 0, $per_page = 50) {
		$params = array(
			'access_token' => $token,
			'count'        => $per_page,
			'offset'       => $page * $per_page
		);
		if ($gid) $params['uid'] = $uid;
		$result = json_decode($this->makeRequest($this->getUrl(
			'api',
			'audio.get',
			$params
		)));		
		if (isset($result->error) && $result->error->error_code > 0) {
			echo $result->error->error_msg;
			exit;
		}
		return $result->response;
	}
	
	public function searchAudios($token, $q, $page = 0, $per_page = 50) {
		$params = array(
			'access_token'  => $token,
			'q'             => $q,
			'auto_complete' => 1,
			'count'         => $per_page,
			'offset'        => $page * $per_page
		);
		if ($gid) $params['gid'] = $gid;
		$result = json_decode($this->makeRequest($this->getUrl(
			'api',
			'audio.search',
			$params
		)));		
		if (isset($result->error) && $result->error->error_code > 0) {
			echo $result->error->error_msg;
			exit;
		}
		return $result->response;
	}
	
	public function getAudioUrlById($token, $vkid) {
		$params = array(
			'access_token'  => $token,
			'audios'        => $vkid
		);
		$result = json_decode($this->makeRequest($this->getUrl(
			'api',
			'audio.getById',
			$params
		)));		
		if (isset($result->error) && $result->error->error_code > 0) {
			echo '['.$result->error->error_code . '] '.$result->error->error_msg;
			exit;
		}
		return $result->response;
	}
	
	public function getGroups() {
		$result = json_decode($this->makeRequest($this->getUrl(
			'api',
			'getGroupsFull',
			array(
				'access_token' => $this->appToken
			))
		));		
		if (isset($result->error) && $result->error->error_code > 0) {
			echo $result->error->error_msg;
			exit;
		}
		return $result->response;
	}
	
	public function getUserDetails($uid = false) {
		$result = json_decode($this->makeRequest($this->getUrl(
			'api',
			'getProfiles',
			array(
				'uid'          => $uid ? $uid : $this->userID,
				'access_token' => $this->appToken,
				'fields'       => 'first_name,last_name,photo,photo_medium,city'
			))
		));		
		if (isset($result->error) && $result->error->error_code > 0) {
			echo $result->error->error_msg;
			exit;
		}
		if (!isset($result->response) || !isset($result->response[0]->first_name)) return false;
		return $result->response[0];
	}
	
	public function getUserInGroup($gid = false,$uid = false, $token = false) {
		$result = json_decode($this->makeRequest($this->getUrl(
			'api',
			'groups.isMember',
			array(
				'gid'          => $gid,
				//'uid'          => $uid ? $uid : $this->userID,
				'access_token' => $token ? $token : $this->appToken
			))
		));		
		if (isset($result->error) && $result->error->error_code > 0) {
			echo $result->error->error_msg;
			exit;
		}
		return $result->response;
	}
}
?>
