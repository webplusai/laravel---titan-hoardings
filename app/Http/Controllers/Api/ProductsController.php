<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use App\JobProduct;
use App\ProductPrice;
use Auth;

class ProductsController extends Controller
{

	/**
	 * Gets a list of products for a single job.
	 */
	public function getListByJob($job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		$products = [];

		foreach ($job->products as $job_product) {
			$products[] = [
				'id'           => $job_product->product_id,
				'name'         => $job_product->product->name,
				'quantity'     => $job_product->quantity,
				'price'        => $job_product->price,
				'is_collected' => (bool) $job_product->is_collected,
			];
		}

		return response()->json($products);
	}

	/**
	 * Gets a list of all products the installer's agent has access to.
	 */
	public function getList($agent_id)
	{
		$this->ensureUserIsInstallerForAgent($agent_id);

		$product_prices = ProductPrice::whereAgentId($agent_id)
									  ->where('type', '!=', 'N')
									  ->get();

		$products = [];

		foreach ($product_prices as $product_price) {
			$products[] = [
				'id'    => $product_price->product_id,
				'name'  => $product_price->product->name,
				'price' => $product_price->product->getPriceForAgent($product_price->agent, $product_price->product_id),
			];
		}

		return response()->json($products);
	}

	/**
	 * Adds a product to a job.
	 */
	public function postAdd(Request $request, $job_id)
	{
		$this->validate($request, [
			'product_id' => 'required',
			'quantity'   => 'required',
		]);

		$job = Job::findOrFail($job_id);
		$this->ensureUserIsPrimaryInstallerFor($job);

		if (JobProduct::whereJobId($request->job_id)->whereProductId($request->product_id)->exists()) {
			return response()->json(['error' => ['Product already added in this job.']], 422);
		}

		$job_product = new JobProduct;
		$job_product->job_id = $job_id;
		$job_product->product_id = $request->product_id;
		$job_product->price = Product::getPriceForAgent($job->agent, $request->product_id);
		$job_product->quantity = $request->quantity;
		$job_product->is_collected = (bool) $request->is_collected;
		$job_product->save();

		JobNotification::create([
			'user_id'  => $this->user->id,
			'agent_id' => $job->agent_id,
			'job_id'   => $job_id,
			'message'  => 'Added a job product '.ucwords($job_product->product->name),
			'title'    => 'Added a Job product',
			'type'     => 'info'
		]);
	}

	/**
	 * Edits a product on a job.
	 */
	public function postEdit(Request $request, $job_id, $product_id)
	{
		$this->validate($request, [
			'quantity'  => 'required',
		]);

		$job = Job::findOrFail($job_id);
		$this->ensureUserIsPrimaryInstallerFor($job);

		$job_product = JobProduct::whereJobId($request->job_id)->whereProductId($request->product_id)->firstOrFail();
		$job_product->quantity = $request->quantity;
		$job_product->is_collected = (bool) $request->is_collected;
		$job_product->save();

		JobNotification::create([
			'user_id'  => $this->user->id,
			'agent_id' => $job->agent_id,
			'job_id'   => $job->id,
			'message'  => 'Modified a job product '.ucwords($job_product->product->name),
			'title'    => 'Modified a Job product',
			'type'     => 'info'
		]);
	}

}
