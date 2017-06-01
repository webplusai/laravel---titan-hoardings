<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Http\Request;
use App\Device;
use App\User;
use Hash;

class AuthController extends Controller
{

	public function postAuthenticate(Request $request)
	{
		$this->validate($request, [
			'email'             => 'required',
			'password'          => 'required',
		]);

		$user = User::whereEmail($request->email)->first();

		if (!$user || !Hash::check($request->password, $user->password)) {
			abort(401);
		}

		if ($user->api_token == null) {
			$user->api_token = md5(microtime());
			$user->save();
		}

		return response()->json(['token' => $user->api_token]);
	}

}
