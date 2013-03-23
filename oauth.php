<?php
class oAuth{
	protected $appId;
	protected $appSecret;
	protected $appUri;
	protected $appToken;
	protected $userID;
	
	public $APARAMS    = array();
	public $ATYPE      = 'code';
	public $TPARAMS    = array();
	public $TTYPE      = 'GET';
	
	public $lastResponse = '';
	
	public function __construct($config){
		$this->appId     = $config['appId'];
		$this->appSecret = $config['secret'];
		$this->appUri    = isset($config['redirect_uri']) ? $config['redirect_uri'] : 'http://yadisk.helpcast.ru/index.php?login=1';
	}
	public function getLoginToken(){
		return $this->appToken;
	}
	public function setAppUri($redirect){
		$this->appUri = $redirect;
	}
	public function setUser($user){
		$this->appToken = $user['user_token'];
		$this->userID   = $user['user_id'];
	}
	public function getLoginUrl(){
		$login_params = $this->APARAMS;
		$login_params['client_id']     = $this->appId;
		$login_params['redirect_uri']  = $this->appUri;
		$login_params['response_type'] = $this->ATYPE;
		return $this->getUrl('oauths', $this->AMETHOD, $login_params);
	}
	public function getUser(){
		if (!isset($_GET[$this->ATYPE])) return false;
		$login_params = $this->TPARAMS;
		$login_params['client_id']     = $this->appId;
		$login_params['client_secret'] = $this->appSecret;
		$login_params['code']          = $_GET[$this->ATYPE];
		if (isset($login_params['redirect_uri'])) $login_params['redirect_uri'] = $this->appUri;
		if ($this->TTYPE == 'POST') {
			$result = $this->makeRequest($this->getUrl('oauths', $this->TMETHOD), $login_params);
		} else {
			$result = $this->makeRequest($this->getUrl('oauths', $this->TMETHOD, $login_params));
		}
	    $result = json_decode($result);
	    if (!isset($result->access_token)) return false;
	    $this->appToken = $result->access_token;
	    $this->userID = isset($result->user_id) ? $result->user_id : $this->getIDByResult($result);
	    return $this->userID;
	}
	protected function makeRequest($url, $fields = array(), $headers = array(), $post = false){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($fields){
			$fields_string = '';
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }
			rtrim($fields_string, '&');
			curl_setopt($ch, CURLOPT_POST, count($fields));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		}
		if ($headers) {
	    	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    }
	    if ($post) {
			curl_setopt($ch, CURLOPT_POST, 1);
		}
		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		
		$result = curl_exec($ch);
		$info   = curl_getinfo($ch);
		//echo '<br/><br/>'; print_r($info['request_header']); echo '<br/><br/>';
		curl_close($ch);
		$this->lastResponse = $result;
	    return $result;
	}
	protected function getUrl($name, $path='', $params=array()){
		$url = $this->DOMAIN_MAP[$name];
		if ($path) {
			if ($path[0] === '/') {
				$path = substr($path, 1);
			}
			$url .= $path;
		}
		if ($params) $url .= '?' . http_build_query($params, null, '&');
		return $url;
	}
}