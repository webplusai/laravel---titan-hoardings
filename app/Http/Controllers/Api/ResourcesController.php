<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class ResourcesController extends Controller
{

	public function getList(Request $request)
	{
		$this->validate($request, [
			'product_id' => 'required',
		]);

		$resources = DB::table('product_resources AS pr')
		               ->join('resources AS r', 'r.id', '=', 'pr.resource_id')
		               ->where('pr.product_id', $request->product_id)
		               ->orderBy('r.name')
		               ->selectRaw("
		                   r.id,
		                   r.name,
		                   r.description,
		                   r.type,
		                   IF(r.type != 'video', CONCAT(?, r.url), r.url) AS url", [$request->root() . '/']
					   )
					   ->get();

		return response()->json($resources);
	}

	public function postView($resource_id)
	{
		DB::insert("INSERT INTO resource_views (resource_id, user_id, last_viewed_at) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE last_viewed_at = VALUES(last_viewed_at)", [
			$resource_id,
			Auth::guard('api')->id(),
		]);
	}

}
