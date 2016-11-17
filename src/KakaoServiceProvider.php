<?php
namespace pouu69\KakaoApi;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\App;

use Exception;
use pouu69\KakaoApi\Kakao;

class KakaoServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$app = $this->app ?: app();

		$this->mergeConfigFrom(__DIR__.'/config/config.php', 'kakao');

		$this->publishes([
			__DIR__.'/config/config.php' => config_path('kakao.php'),
		]);


		$this->app[Kakao::class] = $this->app->share(function($app)
		{
			return new Kakao();
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['kakao'];
	}

}