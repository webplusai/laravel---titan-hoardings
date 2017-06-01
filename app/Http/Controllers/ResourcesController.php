<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Resource;
use App\ProductResource;

class ResourcesController extends Controller
{

	public function getIndex()
	{
		$resources = Resource::orderBy('name')->get();
		$products = Product::orderBy('name')->get();

		return view('pages.resources-index')
			->with('title', 'Resources')
			->with('resources', $resources)
			->with('products', $products);
	}

	public function getCreate()
	{
		$products = Product::orderBy('name')->get();

		return view('modals.resources-create')
			->with('products', $products);
	}

	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'        => 'required',
			'description' => 'required',
			'type'        => 'required|in:file,image,video',
			'file'        => 'required_if:type,file|file',
			'image'       => 'required_if:type,image|image',
			'video'       => 'required_if:type,video|url',
			'product_ids' => 'array',
		]);

		$resource = new Resource;
		$resource->name = $request->name;
		$resource->description = $request->description;
		$resource->type = $request->type;

		if ($resource->type == 'video') {
			$resource->url = $request->video;
		}

		$resource->save();

		if ($request->hasFile($resource->type)) {
			$resource->setFile($request->file($resource->type));
		}

		$resource->products()->sync((array) $request->product_ids);
	}

	public function getEdit($resource_id)
	{
		$resource = Resource::findOrFail($resource_id);
		$products = Product::orderBy('name')->get();
		$product_resources = ProductResource::whereResourceId($resource->id)->pluck('product_id')->toArray();

		return view('modals.resources-edit')
			->with('resource', $resource)
			->with('products', $products)
			->with('product_resources', $product_resources);
	}

	public function postEdit(Request $request, $resource_id)
	{
		$this->validate($request, [
			'name'        => 'required',
			'description' => 'required',
			'file'        => 'file',
			'image'       => 'image',
			'video'       => 'url',
			'product_ids' => 'array',
		]);

		$resource = Resource::findOrFail($resource_id);
		$resource->name = $request->name;
		$resource->description = $request->description;

		if ($resource->type == 'video') {
			$resource->url = $request->video;
		}

		$resource->save();

		if ($request->hasFile($resource->type)) {
			$resource->setFile($request->file($resource->type));
		}

		$resource->products()->sync((array) $request->product_ids);
	}

	public function getDelete($resource_id)
	{
		$resource = Resource::findOrFail($resource_id);

		return view('modals.resources-delete')
			->with('resource', $resource);
	}

	public function postDelete($resource_id)
	{
		$resource = Resource::findOrFail($resource_id);
		$resource->delete();
	}

}
