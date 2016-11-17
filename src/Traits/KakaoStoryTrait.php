<?php namespace pouu69\KakaoApi\Traits;


Trait KakaoStoryTrait{
	/**
	 * 카카오스토리 사용자 유무체크
	 * @param  string  			$accessToken 사용자 엑세스토큰
	 * @return object|array              response
	 */
	public function isStoryUser($accessToken){
		$requestAPI = $this->API_VERSION.'/api/story/isstoryuser';

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

	public function postNote(){

	}

	/**
	 * image upload
	 * @param  array 				$files       upload할 이미지
	 * @param  string 			$accessToken 사용자 엑세스 토큰
	 * @return object|array              response
	 */
	public function postImageUpload($files, $accessToken){
		$requestAPI = $this->API_VERSION.'/api/story/upload/multi';

		$header = [
      'Authorization' => "Bearer {$accessToken}"
		];

		$options = [
			'headers' => $header,
			'multipart' => $files
		];

		return $this->post($this->API_TYPE, $requestAPI, $options);
	}

	/**
	 * 사진포함 포스팅 하기
	 * @param  array 				$data        전송할 데이터
	 * @param  string 			$accessToken 사용자 엑세스토큰
	 * @return object|array              response
	 */
	public function postPhoto($data, $accessToken){
		$requestAPI = $this->API_VERSION.'/api/story/post/photo';

		$header = [
			'Content-Type' => "application/x-www-form-urlencoded;charset=utf-8",
      'Authorization' => "Bearer {$accessToken}"
		];

		$data = [
			'permission'			=> $data['permission'],
			'enable_share'		=> 'true',
			'content' 				=> $data['content'],
			'image_url_list' 	=> json_encode($data['image_url_list'])
		];

		$options = [
			'headers'		=> $header,
			'form_params'	=> $data
		];

		return $this->post($this->API_TYPE, $requestAPI, $options);
	}
}