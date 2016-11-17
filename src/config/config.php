<?php

// You can find the keys here : https://apps.twitter.com/

return [
	'AUTH_URL'			=> 'https://kauth.kakao.com',
	'API_URL'      	=> 'https://kapi.kakao.com',
	'API_VERSION'  	=> '/v1',

	'REDIRECT_URL' 	=> env('KAKAO_URL',''),

	'API_KEY'   => env('KAKAO_KEY', ''),
	'ADMIN_KEY' => env('KAKAO_ADMIN_KEY', ''),
];