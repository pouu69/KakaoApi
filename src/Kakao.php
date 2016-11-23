<?php namespace pouu69\KakaoApi;

use pouu69\KakaoApi\Traits\AuthTrait;
use pouu69\KakaoApi\Traits\KakaoStoryTrait;
use pouu69\KakaoApi\Traits\UserTrait;

use \GuzzleHttp\Exception\RequestException;
use Exception;

class Kakao extends KakaoRequest{
	use AuthTrait, KakaoStoryTrait, UserTrait;

	/** @var string request URI type */
	protected $AUTH_TYPE		= 'AUTH';
	protected $API_TYPE			= 'API';

	/** @var string Request URL */
	protected $API_URL			= '';
	protected $AUTH_URL			= '';
	protected $REDIRECT_URL = '';

	/** @var string kakao admin , api key */
	private $_API_KEY				= '';
	private $_ADMIN_KEY			= '';

	protected $API_VERSION = '';

	public function __construct(){
		$this->_API_KEY			= config('kakao.API_KEY');
		$this->_ADMIN_KEY		= config('kakak.ADMIN_KEY');
		$this->REDIRECT_URL = config('kakao.REDIRECT_URL');

		$this->API_VERSION = config('kakao.API_VERSION');

		$this->AUTH_URL			= config('kakao.AUTH_URL');
		$this->API_URL			= config('kakao.API_URL');
	}

	/**
	 * http request 하기
	 * @param  string $method     request type(get|post)
	 * @param  string $requestAPI request API 주소
	 * @param  string $host       base URL
	 * @param  array 	$options    전송 데이터 또는 헤더 등등 옵션
	 * @return array|object       response
	 */
	public function query($method, $requestAPI, $host, $options){
		$client = new \GuzzleHttp\Client([
			'base_uri' => $host
		]);

		try{
			$response = $client->request($method, $requestAPI, $options);
			return $this->makeResponse($response);
		}catch (RequestException $e) {
			return $this->makeErrorResponse($e);
		}
	}

	/**
	 * response 결과 정제
	 * @param  object $response response 결과
	 * @return array 						respoonse 를 정제한 결과           
	 */
	protected function makeResponse($response){
		$result = [
			'code'		=> $response->getStatusCode(), // 200
			'reason'	=> $response->getReasonPhrase(), // OK
			'body'		=> $response->getBody(),
			'contents' => json_decode($response->getBody()->getContents())
		];

		return $result;
	}

	/**
	 * error response 정제
	 * @param  object $e error object
	 * @return object    json
	 */
	protected function makeErrorResponse($e){
			$errorResponse = $e->getResponse();
			$errorCode = $errorResponse->getStatusCode();
			$errorBody = json_decode($errorResponse->getBody(), true, 512, JSON_BIGINT_AS_STRING);

			return [
				'code' => $errorCode,
				'body' => $errorBody
			];
	}

	/**
	 * request type (api|auth)
	 * @param  string $type api|auth 인지 request type 체크
	 * @return string       request type에 맞는 URL
	 */
	public function getRequestType($type){
		if($type === 'AUTH'){
			return $this->AUTH_URL;
		}else if($type === 'API'){
			return $this->API_URL;
		}
	}
}