<?php namespace Anchu\Ftp;

use Illuminate\Support\ServiceProvider;

class FtpServiceProvider extends ServiceProvider {

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
		$this->package('anchu/ftp');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app['ftp'] = $this->app->share(function($app)
        {
            return new FtpManager($app);
        });
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            // alias 'Ftp' Will be removed soon
            $loader->alias('Ftp', 'Anchu\Ftp\Facades\Ftp');
            $loader->alias('FTP', 'Anchu\Ftp\Facades\Ftp');
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('ftp');
	}

}