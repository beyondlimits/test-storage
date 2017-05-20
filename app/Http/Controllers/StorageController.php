<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Filesystem\Filesystem;

# TODO: Upload and download should be made on streams!

class StorageController extends Controller
{
	protected $storage;
	protected $request;

	public function __construct(Request $request, Filesystem $storage)
	{
		$this->request = $request;
		$this->storage = $storage;
	}

	public function handle($path)
	{
		$method = $this->request->method();

		return $this->{$method}($path);
	}

	public function get($path)
	{
		if (!$this->storage->exists($path)) {
			abort(404, 'File not found');
		}

		return response($this->storage->get($path), 200, [
			'Content-Type' => $this->storage->mimeType($path),
		]);
	}

	public function post($path)
	{
		if ($this->storage->exists($path)) {
			abort(409, 'File exists');
		}

		$this->storage->put($path, $this->request->getContent());

		return response('OK');
	}

	public function put($path)
	{
		if (!$this->storage->exists($path)) {
			abort(404, 'File not found');
		}

		$this->storage->put($path, $this->request->getContent());

		return response('OK');
	}

	public function delete($path)
	{
		if (!$this->storage->exists($path)) {
			abort(404, 'File not found');
		}

		$this->storage->delete($path);

		return response('OK');
	}
}
