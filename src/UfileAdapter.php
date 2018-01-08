<?php

namespace Hlf\FlysystemUfile;

use App\Exceptions\ResponseException;
use Hlf\UcloudUfileSdk\UfileSdk;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Adapter\Polyfill\NotSupportingVisibilityTrait;
use League\Flysystem\Adapter\Polyfill\StreamedCopyTrait;
use League\Flysystem\Adapter\Polyfill\StreamedTrait;
use League\Flysystem\Config;
use LogicException;

class UfileAdapter extends AbstractAdapter
{
	use NotSupportingVisibilityTrait, StreamedTrait, StreamedCopyTrait;

	/** @var \Hlf\UcloudUfileSdk\UfileSdk */
	protected $client;
	protected $config;

	public function __construct(UfileSdk $client, $config)
	{
		$this->client = $client;
		$this->config = $config;
	}

	public function createDir($dirname, Config $config)
	{
		throw new LogicException(get_class($this) . ' does not support ' . __FUNCTION__);
	}

	public function delete($path)
	{
		return $this->client->delete(
			$this->applyPathPrefix($path)
		);
	}

	public function deleteDir($dirname)
	{
		throw new LogicException(get_class($this) . ' does not support ' . __FUNCTION__);
	}

	public function getMetadata($path)
	{
		return $this->client->meta(
			$this->applyPathPrefix($path)
		);
	}

	public function getMimetype($path)
	{

		return [
			'mimetype' => $this->client->mime(
				$this->applyPathPrefix($path)
			)
		];
	}

	public function getSize($path)
	{
		return [
			'size' => $this->client->size(
				$this->applyPathPrefix($path)
			)
		];
	}

	public function getTimestamp($path)
	{
		return [
			'timestamp' => strtotime(
				$this->getMetadata($this->applyPathPrefix($path))['Last-Modified']
			)
		];
	}

	public function has($path)
	{
		return $this->client->exists(
			$this->applyPathPrefix($path)
		);
	}

	public function listContents($directory = '', $recursive = false)
	{
		throw new LogicException(get_class($this) . ' does not support ' . __FUNCTION__);
	}

	public function read($path)
	{
		$content = $this->client->get(
			$this->applyPathPrefix($path)
		);

		return ['contents' => $content];
	}

	public function rename($path, $newpath)
	{
		$content = $this->read($path)['contents'];
		$header = [
			'header' => [
				'Content-type' => $this->getMimetype($path)['mimetype']
			]
		];
		$config = new Config($header);

		$this->write($newpath, $content, $config);

		return $this->delete($path);
	}

	public function update($path, $contents, Config $config)
	{
		return $this->write($path, $contents, $config);
	}

	public function write($path, $contents, Config $config)
	{
		return $this->client->put(
			$this->applyPathPrefix($path),
			$contents,
			$config->get('header')
		);
	}

	public function getUrl($path)
	{
		return sprintf('http://%s%s/%s', $this->config['bucket'], $this->config['suffix_cdn'], $this->applyPathPrefix($path));
	}
}