<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ServiceRequestsController extends Controller
{
	public function getIndex(Request $request) {
//		$query = BookingRequest::orderBy('id', 'desc');
//
//		$query->where(function ($query) use ($request) {
//			$query->orWhere('name', 'like', '%' . $request->global_search . '%');
//			$query->orWhere('email', 'like', '%' . $request->global_search . '%');
//		});
//
//		$booking_requests = $query->paginate(25);

		$service_requests = [];
		return view('pages.requests.service-requests.service-requests-list')
			->with('service_requests', $service_requests)
			->with('title', 'Service Requests List');
	}

	public function getView(Request $request) {

	}

	public function postCreate(Request $request) {

	}

	public function postEdit(Request $request) {

	}

	public function postDelete(Request $request) {

	}
}
