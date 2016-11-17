<?php namespace pouu69\KakaoApi\Traits;

Trait AuthTrait{
	/**
	 * 카카오 로그인 하기
	 * @param  array  $query 	로그인 쿼리
	 * @return string 				redirect url     
	 */
	public function getLogin($query=[]){
		$requestAPI = '/oauth/authorize';
		$query = '?client_id='.$this->_API_KEY.'&redirect_uri='.$this->REDIRECT_URL.'&response_type=code';
		return $this->AUTH_URL.$requestAPI.$query;
	}

	/**
	 * 카카오 로그아웃 (세션 해제)
	 * @param  string $accessToken 	사용자 토큰
	 * @return array              	response
	 */
	public function postLogout($accessToken){
		$requestAPI = $this->API_VERSION.'/user/logout';

		$headers = [
      'Authorization' => "Bearer {$accessToken}"
		];

		$options = [
			'headers' => $headers
		];

		return $this->post($this->API_TYPE, $requestAPI, $options);
	}

	/**
	 * token 유효성 검사
	 * @param  string $accessToken 사용자 엑세스 토큰 
	 * @return array              response
	 */
	public function getInfoAccessToken($accessToken){
		$requestAPI = $this->API_VERSION.'/user/access_token_info';

		$headers = [
			'Content-Type' => 'application/x-www-form-urlencoded',
      'Authorization' => "Bearer {$accessToken}"
		];

		$options = [
			'headers' => $headers
		];

		return $this->get($this->API_TYPE, $requestAPI, $options);
	}

	/**
	 * access token 발급
	 * @param  string $code authrize code
	 * @return array       	response
	 */
	public function postAccessToken($code){
		$requestAPI = '/oauth/token';
		$data = [
			'grant_type' 		=> 'authorization_code',
			'client_id' 		=> $this->_API_KEY,
			'redirect_uri'	=> $this->REDIRECT_URL,
			'code'					=> $code
		];

		$headers = [
			'Content-Type' => 'application/x-www-form-urlencoded'
		];

		$options = [
			'headers' => $headers,
			'form_params' => $data
 		];
 		
		return $this->post($this->AUTH_TYPE, $requestAPI, $options);
	}

	/**
	 * toekn 새로 발급
	 * @param  string $refreshToken 사용자 refresh token
	 * @return array 	              response
	 */
	public function postRefreshToken($refreshToken){
		$requestAPI = '/oauth/token';

		$data = [
			'grant_type' 		=> 'refresh_token',
			'client_id' 		=> $this->_API_KEY,
			'refresh_token' => $refreshToken
		];

		$options = [
			'form_params' => $data
 		];
 		
		return $this->post($this->AUTH_TYPE, $requestAPI, $options);
	}
}