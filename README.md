# KakaoApi
> 라라벨에서 사용할 라라벨용 Kakao API 가 없길래 간단하게 몇몇 API 만 만들어 봤습니다.

- Kakao API for Laravel 5
- 카카오 사용자 정보, 카카오 스토리 포스팅 몇개만 api를 지원합니다.

# require

```` php
"guzzlehttp/guzzle": "^6.0",
"php": ">=5.5.0"
````

# Installation
프로젝트에 있는 composer.json에 다음을 추가 하시거나,

```` php
{
    "require": {
        "pouu69/kakao-api": "^1.0"
    }
}
````

composer 를 이용하여 설치 할 수 있습니다.

```` php
composer require pouu69/kakao-api
````

# ServiceProvider
`config/app.php`에 아래 와 같이 providers에 등록을 합니다.

```` php
'providers' => [
    pouu69\KakaoApi\KakaoServiceProvider::class,
]
````

# Facade
Facade 등록을 통해 alias를 등록 하는 경우 다음과 같이 추가 하시면 됩니다.

```` php
'aliases' => [
    'Kakao' => pouu69\KakaoApi\Facade\KakaoFacade::class,
];
````

# config
`config` 폴더에 config 파일을 생성해야합니다.

다음과 같은 내용을 가진 `kakao.php` 파일을 생성합니다.

```` php
<?php

return [
	'AUTH_URL'			=> 'https://kauth.kakao.com',
	'API_URL'      	=> 'https://kapi.kakao.com',
	'API_VERSION'  	=> '/v1',

	'REDIRECT_URL' 	=> env('KAKAO_URL',''),

	'API_KEY'   => env('KAKAO_KEY', ''),
	'ADMIN_KEY' => env('KAKAO_ADMIN_KEY', ''),
];
````  

`.env` 에 kakao에서 발급받은 *KEY* 와 *REDIRECT_URL*을 등록 해놓습니다.


# 제공하는 Kakao API 
- 기본 카카오 사용자 API
 - 로그인
 - 로그아웃
 - 사용자 토큰 발급
 - 사용자 토큰 유효성 검사 및 정보 얻기
 - 사용자 토큰 갱신
 - 사용자 정보 요청
- 카카오 스토리 API
 - 사용자 확인
 - 글 포스팅(only 글)
 - 사진(photo) 포스팅
  - 사진 업로드
  - 퍼블리싱(포스팅)

# API 사용
## 기본 설정

```` php
// 사용하는 곳에다가 등록
use Kakao;
````

## 카카오 로그인 / 사용자 토큰 발급 / 사용자 정보 요청
쿼리파라미터를 추가한 배열을 전달합니다.

```` php
try{
    $url = Kakao::getLogin();
    return redirect()->to($url);
}catch(Exception $e){
    var_dump('kakaoLogin error : ',$e->getMessage());
}	
````

- 콜백으로 실행되는 메서드

```` php
$code = $request->input()['code'];  //  $code 는 콜백 URL에 쿼리로 들어온 authorize_code 이다.

// 카카오 로그인 이후 발급 받은 `authorize_code` 로 수행한다.
$result = Kakao::postAccessToken($code);

if(($result['code'] < 200 || $result['code'] > 206)){
	// 에러 발생
	// $result['body']['error_description'] 에러메세지
}

if(!empty($result['contents']->access_token)){
    $accessToken = $result['contents']->access_token;
    $refreshToken = $result['contents']->refresh_token;

    // 사용자 정보 가져오기
    $credentials = Kakao::getCredential($accessToken);

    //token 세션 저장( 본인에 맞게 수행 )
    Session::put('kakao_access_token', $accessToken);
    Session::put('kakao_refresh_token',$refreshToken);
    
    // 여기서 로그인 작업이나, 사용자 DB 작업 수행
}
````

## 로그아웃
- 세션을 현재 해당 기기만 해제 해줍니다.

```` php
$result = Kakao::postLogout($accessToken);
````

## 사용자 토큰 유효성 검사 및 정보 얻기 / 사용자 토큰 갱신
- access_token 유효성 검사 (12~24시간이 대부분 만료시간이기 때문에)
- refresh_token 이 있어야 합니다.
- 그리고 토큰을 갱신합니다.

```` php
if(session()->has('kakao_access_token') && session()->has('kakao_refresh_token')){
  $kakaoAccessToken = session()->get('kakao_access_token');
  $accessTokenInfo = Kakao::getInfoAccessToken($kakaoAccessToken);
  if($accessTokenInfo['code'] !== 200){
      $tokens = Kakao::postRefreshToken($kakaoAccessToken);
      if($token['code']!== 200){
        // error handling
      }else{
          Session::put('kakao_access_token', $tokens['contents']->access_token);
          if(isset($tokens['contents']->refresh_token)){
              Session::put('kakao_refresh_token',$tokens['contents']->refresh_token);
          }
      }
    }
}
````

## 카카오 스토리 API - 사용자 확인
- 카카오 스토리 사용자 인지 확인

```` php
  	try{
  		// 카카오스토리 사용자 인지 확인합니다.
  		$result = Kakao::isStoryUser(session()->get('kakao_access_token'));
  		$this->response($result)['contents'];
      return $result;
  	}catch(Exception $e){
      $error = json_decode($e->getMessage());
      // error handling
  	}
````
## 카카오 스토리 API - 글 포스팅(only 글)
- 오로지 글만 포스팅 합니다.

```` php
try{
  $content = '이것이 내용입니다.';
  $result = Kakao::postNote($content, session()->get('kakao_access_token'));
}catch(\Exception $e){
  var_dump($e->getMessage());
}
````

## 카카오 스토리 API - 사진 포스팅
### 사진 업로드
> 항상 사진을 업로드 한 이후에 포스팅 해야합니다.

```` php
// 올릴 사진들을 guzzle이 원하는 포맷에 맞게 만듭니다.

$imageUrl = []
$i = 0;
foreach($images as $imagePath){
	$fileDir = // 이미지가 저장되어 있는 로컬 절대 경로
	$fileName = // 이미지 이름
  $file = [
  	'name' 			=> 'file['.$i++.']',
  	'contents'	=> fopen($fileDir,'r'),
  	'filename'	=> $fileName
  ];
  $imageUrl[] = $file;
}

try{
	// 위에서 작업한 이미지를 넘기며, 이미지를  카카오에 업로드합니다.
	$result = Kakao::postImageUpload($imageUrl, session()->get('kakao_access_token'));
  return $this->response($result)['contents'];// 여기에 실제 업로드 할때 필요한 정보가 담겨있습니다. 또는 throw exception 됌
}catch(Exception $e){
  throw new Exception($e->getMessage());
}	
````

### 퍼블리싱(포스팅)
- 위 사진 업로드 작업 이어서 진행

```` php
try{
	// request data 형식
	$data = [
		'image_url_list'	=>	$imageUrlInfos,
		'content'					=>	'메세지 작성하기',
		'permission'			=>	// 포스팅 할 스토리를 전체 공개할지 친구 공개할지 여부. F : 친구에게만 공개, A : 전체 공개, M : 나만 보기 , 기본값은 A.	
	];

	// 이제 최종 퍼블리싱 하기
	$result = Kakao::postPhoto($data, session()->get('kakao_access_token'));
	return $this->response($result);
}catch(Exception $e){
      throw new Exception($e->getMessage());
}
````

# License
The MIT License (MIT).
