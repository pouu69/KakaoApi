<?php 
namespace pouu69\KakaoApi\Facade;

use Illuminate\Support\Facades\Facade;

class KakaoFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pouu69\KakaoApi\Kakao';
    }

}
