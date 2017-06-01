<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\QuoteRequest;

class RequestsController extends Controller
{
    //
	public function postInstallationQuoteRequests(Request $request) {


	}

	public function postModificationQuoteRequests(Request $request) {
		$this->validate($request, [
			'client_id'                 => 'required|exists:clients,id',
			'agent_id'                  => 'required|exists:agents,id',
			'installation_id'           => 'required',
			'request_type'              => 'required|in:1,2,3',
			'modification_required'     => 'required|in:1,2,3,4,5,6',
			'modification_length'       => 'required|numeric',
			'photos'                    => '',
			'notes'                     => '',
			'status'                    => 'required|in:1,2'
		]);

		QuoteRequest::create($request);

	}
}
