<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\BookingRequest;

class BookingRequestsController extends Controller
{
	public function getIndex(Request $request) {
		$query = BookingRequest::orderBy('id', 'desc');

//		$query->where(function ($query) use ($request) {
//			$query->orWhere('name', 'like', '%' . $request->global_search . '%');
//			$query->orWhere('email', 'like', '%' . $request->global_search . '%');
//		});

		$booking_requests = $query->paginate(25);

		return view('pages.requests.booking-requests.booking-requests-list')
			->with('booking_requests', $booking_requests)
			->with('title', 'Booking Requests List');
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
