<?php namespace pouu69\KakaoApi;

abstract class KakaoRequest {
	
	/**
	 * get type HTTP request
	 * @param  string $type       request가 auth|api 인지 체크
	 * @param  string $requestAPI 요청 API Url
	 * @param  array  $options    옵션(데이터)
	 * @return array|object       response
	 */
	public function get($type, $requestAPI, $options=[]){
		$host = $this->getRequestType($type);
 		return $this->query('get', $requestAPI, $host, $options);
	}

	/**
	 * post type HTTP request
	 * @param  string $type       request가 auth|api 인지 체크
	 * @param  string $requestAPI 요청 API Url
	 * @param  array  $options    옵션(데이터)
	 * @return array|object       response
	 */
	public function post($type, $requestAPI, $options=[]){
		$host = $this->getRequestType($type);
		return $this->query('post', $requestAPI, $host, $options);
	}

	/**
	 * request type (api|auth)
	 * @param  string $type api|auth 인지 request type 체크
	 * @return string       request type에 맞는 URL
	 */
  abstract public function getRequestType($type);
}