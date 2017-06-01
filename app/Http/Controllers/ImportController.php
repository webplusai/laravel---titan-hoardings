<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Invitation;
use App\Job;
use App\User;
use Auth;
use DateTimeZone;
use DB;
use Log;
use Exception;
use InvalidArgumentException;

class ImportController extends Controller
{

	public function getIndex()
	{
		return redirect('/import/clients');
	}

	public function getClients()
	{
		$has_jobs = Job::whereAgentId(Auth::user()->agent_id)->exists();

		return view('pages.import-index')
			 ->with('title', 'Import Data')
			 ->with('type', 'clients')
			 ->with('has_jobs', $has_jobs);
	}

	public function getInstallers()
	{
		$has_jobs = Job::whereAgentId(Auth::user()->agent_id)->exists();

		return view('pages.import-index')
			 ->with('title', 'Import Data')
			 ->with('type', 'installers')
			 ->with('has_jobs', $has_jobs);
	}

	public function postClients(Request $request)
	{
		$this->validate($request, [
			'file'     => 'required|mimes:csv,txt',
			'existing' => 'required|in:delete,merge',
		]);

		DB::beginTransaction();

		try {
			$this->importClients($request);
		} catch (InvalidArgumentException $e) {
			DB::rollback();
			return back()->withErrors([$e->getMessage()]);
		} catch (Exception $e) {
			DB::rollback();
			Log::error($e);
			return back()->withErrors(['Sorry, something went wrong. No data has been changed.']);
		}

		DB::commit();

		return back()->with('message', 'The import was successful.');
	}

	public function postInstallers(Request $request)
	{
		$this->validate($request, [
			'file'     => 'required|mimes:csv,txt',
			'existing' => 'required|in:delete,merge',
		]);

		DB::beginTransaction();

		try {
			$this->importInstallers($request);
		} catch (InvalidArgumentException $e) {
			DB::rollback();
			return back()->withErrors([$e->getMessage()]);
		} catch (Exception $e) {
			DB::rollback();
			Log::error($e);
			return back()->withErrors(['Sorry, something went wrong. No data has been changed.']);
		}

		DB::commit();

		return back()->with('message', 'The import was successful.');
	}

	private function importClients(Request $request)
	{
		DB::statement('SET autocommit = 0');

		$imported_ids = [];

		$fp = fopen($request->file('file'), 'r');
		$line = 1;

		fgetcsv($fp);

		while ($row = fgetcsv($fp)) {
			$line++;

			if (count($row) != 10) {
				throw new InvalidArgumentException("Line $line: Expected 10 columns but found " . count($row) . '.');
			}

			$columns = array_combine(['name','email','billing_email','phone','mobile','fax','size','abn','billing_address','shipping_address'], $row);

			if ($client = Client::whereAgentId(Auth::user()->agent_id)->whereEmail($columns['email'])->first()) {
				$client->update($columns);
			} else {
				$columns['agent_id'] = Auth::user()->agent_id;

				$client = Client::create($columns);
			}

			$imported_ids[] = $client->id;
		}

		if ($request->existing == 'delete' && $imported_ids) {
			DB::table('clients')->whereAgentId(Auth::user()->agent_id)->whereNotIn('id', $imported_ids)->delete();
		}
	}

	private function importInstallers(Request $request)
	{
		DB::statement('SET autocommit = 0');

		$imported_ids = [];

		$fp = fopen($request->file('file'), 'r');
		$line = 1;

		fgetcsv($fp);

		while ($row = fgetcsv($fp)) {
			$line++;

			if ($request->send_invitations && $line > 26) {
				throw new InvalidArgumentException('You can only import 25 installers at a time when sending invitations.');
			}

			if (count($row) != 17) {
				throw new InvalidArgumentException("Line $line: Expected 17 columns but found " . count($row) . '.');
			}

			$columns = array_combine([
				'first_name',
				'last_name',
				'email',
				'abn',
				'billing_address',
				'shipping_address',
				'phone_main',
				'phone_mobile',
				'fax',
				'email_billing',
				'bank_account_name',
				'bank_account_number',
				'banking_bsb',
				'date_of_birth',
				'gender',
				'certification',
				'timezone',
			], $row);

			try {
				new DateTimeZone($columns['timezone']);
			} catch (Exception $e) {
				throw new InvalidArgumentException("Line $line: Timezone '{$columns['timezone']}' is not a valid timezone.");
			}

			$user = User::whereAgentId(Auth::user()->agent_id)->whereType('installer')->whereEmail($columns['email'])->first();

			if ($user) {
				$user->update($columns);
			} else {
				$columns['agent_id'] = Auth::user()->agent_id;
				$columns['type'] = 'installer';

				$user = User::create($columns);
			}

			$imported_ids[] = $user->id;
		}

		if ($request->existing == 'delete' && $imported_ids) {
			DB::table('users')
			  ->whereAgentId(Auth::user()->agent_id)
			  ->whereType('installer')
			  ->whereNotIn('id', $imported_ids)
			  ->delete();
		}

		if ($request->send_invitations) {
			foreach ($imported_ids as $user_id) {
				$this->sendInvitation($user_id);
			}
		}
	}

	private function sendInvitation($user_id)
	{
		$invitation = Invitation::create([
			'user_id' => $user_id,
			'token'   => substr(md5(microtime()), 0, 10),
		]);

		$invitation->send();
	}

}
