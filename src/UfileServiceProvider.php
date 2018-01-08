<?php

namespace Hlf\FlysystemUfile;

use Hlf\UcloudUfileSdk\UfileSdk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class UfileServiceProvider extends ServiceProvider
{

	public function register()
	{
//		dd(__METHOD__);
	}

	public function boot()
	{
		Storage::extend('ufile', function ($app, $config) {
			$client = new UfileSdk(
				$config['bucket'],
				$config['public_key'],
				$config['secret_key'],
				$config['suffix']
			);

			return new Filesystem(new UfileAdapter($client, $config));
		});
	}
}