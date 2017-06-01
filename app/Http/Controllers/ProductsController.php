<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Product;
use App\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Requests;

class ProductsController extends Controller
{

	/**
	 * Show list of all products
	 * @return [type] [description]
	 */
	public function getIndex(Request $request)
	{
		$query = Product::orderBy('id', 'desc');

		if ($request->phrase) {
			$query->where('name', 'like', '%' . $request->phrase . '%');
		}

		$products = $query->paginate(10);

		return view('pages.products-list')
			 ->with('products', $products)
			 ->with('title', 'Product List');
	}

	public function getView($product_id)
	{
		$product = Product::findOrfail($product_id);

		return view('pages.products-view')
			   ->with('product', $product)
			   ->with('title', 'Product View');
	}

	/**
	 * Show create product form
	 * @return view
	 */
	public function getCreate()
	{
		return view('pages.products-create');
	}

	/**
	 * Handle saving of product data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'                      => 'required',
			'default_price'             => 'required',
			'height_of_panel'           => 'required',
			'height_of_dust_supression' => 'required',
			'width'                     => 'required',
			'depth'                     => 'required',
			'weight'                    => 'required',
			'wind_rating'               => 'required',
		]);

		$product = new Product();
		$product->name = $request->name;
		$product->default_price = $request->default_price;
		$product->height_of_panel = $request->height_of_panel;
		$product->height_of_dust_supression = $request->height_of_dust_supression;
		$product->width = $request->width;
		$product->depth = $request->depth;
		$product->weight = $request->weight;
		$product->wind_rating = $request->wind_rating;
		$product->save();

		return redirect('/products');
	}

	/**
	 * Show edit product page
	 * @param  int $product_id
	 * @return view
	 */
	public function getEdit($product_id)
	{
		$product = Product::findOrFail($product_id);

		return view('pages.products-edit')
			 ->with('product', $product)
			 ->with('title', 'Edit Product');
	}

	/**
	 * Handle edit agent data
	 * @param  Request $request
	 * @param  int $product_id
	 * @return view
	 */
	public function postEdit(Request $request, $product_id)
	{
		$this->validate($request, [
			'name'                      => 'required',
			'default_price'             => 'required',
			'height_of_panel'           => 'required',
			'height_of_dust_supression' => 'required',
			'width'                     => 'required',
			'depth'                     => 'required',
			'weight'                    => 'required',
			'wind_rating'               => 'required',
		]);

		$product = Product::find($product_id);
		$product->name = $request->name;
		$product->default_price = $request->default_price;
		$product->height_of_panel = $request->height_of_panel;
		$product->height_of_dust_supression = $request->height_of_dust_supression;
		$product->width = $request->width;
		$product->depth = $request->depth;
		$product->weight = $request->weight;
		$product->wind_rating = $request->wind_rating;
		$product->save();

		return redirect('/products');
	}

	public function getDelete($product_id)
	{
		$product = Product::findOrFail($product_id);

		return view('pages.products-delete')
			 ->with('product', $product)
			 ->with('title', 'Delete Product');
	}

	/**
	 * Delete Product
	 * @param  Request $request
	 * @param  int $product_id
	 * @return redirect
	 */
	public function postDelete($product_id)
	{
		Product::where('id', $product_id)->delete();

		return redirect('/products');
	}

}
