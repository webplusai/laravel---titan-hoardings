<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Http\Requests;
use App\Invitation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class InstallersController extends Controller
{

	public function getIndex(Request $request)
	{
		$installers = $this->user->agent->installers()
			->where(function($query) use($request) {
				$query->orWhere('first_name', 'like', '%' . $request->search . '%');
				$query->orWhere('last_name', 'like', '%' . $request->search . '%');
				$query->orWhere('email', 'like', '%' . $request->search . '%');
			})
			->orderBy('id', 'desc')
			->paginate(25);

		return view('pages.installers-list')
			->with('installers', $installers)
			->with('title', 'Installer List');
	}

	/**
	 * Show installer view page
	 *
	 * @param  int $installer_id
	 * @return view
	 */
	public function getView($installer_id)
	{
		$installer = $this->user->agent->installers()->findOrFail($installer_id);

		return view('pages.installers-view')
			->with('installer', $installer)
			->with('title', 'View Installer');
	}

	/**
	 * Delete Installer
	 *
	 * @param  Request $request
	 * @param  int $user_id
	 * @return redirect
	 */
	public function postDelete(Request $request, $user_id)
	{
		$this->user->agent->installers()->detach($user_id);

		if ($request->ajax()) {
			return ['status' => 'success'];
		}

		return redirect('/installers');
	}

	/**
	 * Show installer invite page
	 *
	 * @return view
	 */
	public function getInvite()
	{
		return view('pages.installers-invite')
			->with('title', 'Invite Installer');
	}

	/**
	 * Handle installer inviting.
	 *
	 * If the installer already exists for another agent, add them to the
	 * current agent too, otherwise create the user and send them an invitation.
	 *
	 * @param  Request $request
	 * @return redirect
	 */
	public function postInvite(Request $request)
	{
		$rules = [
			'first_name' => 'required|max:255',
			'last_name'  => 'required|max:255',
		];

		if (User::where('type', '!=', 'installer')->where('email', $request->email)->first()) {
			$rules['email'] = 'required|email|max:255|unique:users,email';
		} else {
			$rules['email'] = 'required|email|max:255';
		}

		$this->validate($request, $rules, [
			'email.unique' => 'This email address is already in use by an agent or administrator user.',
		]);

		$user = User::where('type', 'installer')->where('email', $request->email)->first();

		if ($user) {
			$this->user->agent->installers()->attach($user->id);
			return redirect('/installers');
		}

		$user = User::create([
			'first_name' => $request->first_name,
			'last_name'  => $request->last_name,
			'email'      => $request->email,
			'type'       => 'installer',
		]);

		$this->user->agent->installers()->attach($user->id);

		$invitation = Invitation::create([
			'token'   => substr(md5(microtime()), 0, 10),
			'user_id' => $user->id,
		]);

		$invitation->send();

		return redirect('/installers');
	}

	public function getResend($installer_id)
	{
		$installer = User::where('type', 'installer')->findOrFail($installer_id);

		return view('modals.installers-resend-invitation')
			->with('installer', $installer);
	}

	public function postResend(Request $request, $installer_id)
	{
		$installer = User::where('type', 'installer')->findOrFail($installer_id);
		$invitation = $installer->invitations->first();

		if (count($invitation)) {
			$invitation->send();
		}
	}

}
