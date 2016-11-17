<?php namespace pouu69\KakaoApi\Traits;

use Exception;

Trait UserTrait{
	/**
	 * 로그인 사용자 정보 가져오기
	 * @param  string $accessToken 사용자 토큰
	 * @return array              	response
	 */
	public function getCredential(string $accessToken){
		$requestAPI = $this->API_VERSION."/user/me";

		$header = [
		  'Accept'     => 'application/json',
			'Content-Type' => "application/x-www-form-urlencoded",
      'Authorization' => "Bearer {$accessToken}"
		];

		$options = [
			'headers' => $header
		];

		return $this->get($this->API_TYPE, $requestAPI, $options);
	}

	public function updateProfile(string $accessToken){
		$requestAPI = $this->API_VERSION."/user/update_profile";
		$header = [
		  'Accept'     => 'application/json',
			'Content-Type' => "application/x-www-form-urlencoded",
      'Authorization' => "Bearer {$accessToken}"
		];
		$options = [
			'headers' => $header
		];
		return $this->post($this->API_TYPE, $requestAPI, $options);

	}
}