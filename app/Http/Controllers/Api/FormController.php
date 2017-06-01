<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use App\JobInstaller;
use App\User;
use Hash;
use Auth;
use DB;
use Carbon\Carbon;
use App\Exceptions\ServiceValidationException;
use App\JsraForm;
use App\QcForm;

class FormController extends Controller
{

	public function getJsra($job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		return response()->json($job->jsra);
	}

	public function postJsra(Request $request, $job_id)
	{
		$this->validate($request, [
			'manual_handling'                         => 'required|in:Y,N',
			'multi_person_lift'                       => 'boolean',
			'trolleys_for_transport'                  => 'boolean',
			'lift_one_at_time'                        => 'boolean',
			'job_rotation_breaks'                     => 'boolean',
			'has_public_access'                       => 'required|in:Y,N',
			'exclusion_zone'                          => 'boolean',
			'awareness_of_people'                     => 'boolean',
			'is_above_2_metres'                       => 'required|in:Y,N',
			'platform_ladder'                         => 'boolean',
			'ewp'                                     => 'boolean',
			'mobile_scaffold'                         => 'boolean',
			'hazardous_material'                      => 'required|in:Y,N',
			'vacuum_dust'                             => 'boolean',
			'respiratory_eye_hearing_ppe'             => 'boolean',
			'has_potential_falling_objects'           => 'required|in:Y,N',
			'two_persons'                             => 'boolean',
			'wear_hardhat'                            => 'boolean',
			'wear_appropriate_ppe'                    => 'required|in:Y,N',
			'ppe_boots'                               => 'boolean',
			'ppe_shirt'                               => 'boolean',
			'ppe_eye_protection'                      => 'boolean',
			'ppe_ears'                                => 'boolean',
			'ppe_gloves'                              => 'boolean',
			'has_other_hazards'                       => 'required|in:Y,N',
			'other_hazards'                           => 'string',
		]);

		$job = Job::findOrFail($job_id);
		$this->ensureUserIsPrimaryInstallerFor($job);

		$job->jsra->update([
			'manual_handling'                         => $request->manual_handling,
			'multi_person_lift'                       => (boolean) $request->multi_person_lift,
			'trolleys_for_transport'                  => (boolean) $request->trolleys_for_transport,
			'lift_one_at_time'                        => (boolean) $request->lift_one_at_time,
			'job_rotation_breaks'                     => (boolean) $request->job_rotation_breaks,
			'has_public_access'                       => $request->has_public_access,
			'exclusion_zone'                          => (boolean) $request->exclusion_zone,
			'awareness_of_people'                     => (boolean) $request->awareness_of_people,
			'is_above_2_metres'                       => $request->is_above_2_metres,
			'platform_ladder'                         => (boolean) $request->platform_ladder,
			'ewp'                                     => (boolean) $request->ewp,
			'mobile_scaffold'                         => (boolean) $request->mobile_scaffold,
			'hazardous_material'                      => $request->hazardous_material,
			'vacuum_dust'                             => (boolean) $request->vacuum_dust,
			'respiratory_eye_hearing_ppe'             => (boolean) $request->respiratory_eye_hearing_ppe,
			'has_potential_falling_objects'           => $request->has_potential_falling_objects,
			'two_persons'                             => (boolean) $request->two_persons,
			'wear_hardhat'                            => (boolean) $request->wear_hardhat,
			'wear_appropriate_ppe'                    => $request->wear_appropriate_ppe,
			'ppe_boots'                               => (boolean) $request->ppe_boots,
			'ppe_shirt'                               => (boolean) $request->ppe_shirt,
			'ppe_eye_protection'                      => (boolean) $request->ppe_eye_protection,
			'ppe_ears'                                => (boolean) $request->ppe_ears,
			'ppe_gloves'                              => (boolean) $request->ppe_gloves,
			'has_other_hazards'                       => $request->has_other_hazards,
			'other_hazards'                           => (string) $request->other_hazards,
		]);

		$job->status = 'jsra-pending-signatures';
		$job->save();
	}

	public function getQc($job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		return response()->json($job->qc);
	}

	public function postQc(Request $request, $job_id)
	{
		$this->validate($request, [
			'good_condition'            => 'required|in:Y,N,NA',
			'hoardings_aligned'         => 'required|in:Y,N,NA',
			'good_paint'                => 'required|in:Y,N,NA',
			'well_lubed'                => 'required|in:Y,N,NA',
			'taped'                     => 'required|in:Y,N,NA',
			'dust_supression_installed' => 'required|in:Y,N,NA',
			'anti_tamper_installed'     => 'required|in:Y,N,NA',
			'do_not_lean_installed'     => 'required|in:Y,N,NA',
			'fingerprints_removed'      => 'required|in:Y,N,NA',
			'floor_swept'               => 'required|in:Y,N,NA',
			'waste_removed'             => 'required|in:Y,N,NA',
			'hoarding_type'             => 'required|in:Kiosk,Impact,Wind Rated',
			'installed_per_plan'        => 'required|in:Y,N,NA',
			'set_out'                   => 'required|numeric',
			'uprights_installed'        => 'required|in:Y,N,NA',
			'stud_spec'                 => 'required|in:70x45mm MGP12,TITAN Eco',
			'double_stud'               => 'required|in:Y,N,NA',
			'panel_installed'           => 'required|in:12mm MDF,16mm WB,18mm ply,50mm EPS',
			'screw_size'                => 'required|in:8Gx40mm,8Gx16mm,12Gx75mm or 14Gx75mm',
			'panel_fixing'              => 'required|in:Pine stud screw,TITAN Eco screw',
			'counterweights_quantity'   => 'required|numeric',
			'counterweights_height'     => 'required|numeric',
			'wind_compliant'            => 'required|in:Y,N,NA',
			'returns'                   => 'required|in:Y,N,NA',
			'bracing'                   => 'required|in:Y,N,NA',
			'certificate'               => 'required|in:Y,N,NA',
		]);

		$job = Job::findOrFail($job_id);
		$this->ensureUserIsPrimaryInstallerFor($job);

		$job->qc->update([
			'good_condition'            => $request->good_condition,
			'hoardings_aligned'         => $request->hoardings_aligned,
			'good_paint'                => $request->good_paint,
			'well_lubed'                => $request->well_lubed,
			'taped'                     => $request->taped,
			'dust_supression_installed' => $request->dust_supression_installed,
			'anti_tamper_installed'     => $request->anti_tamper_installed,
			'do_not_lean_installed'     => $request->do_not_lean_installed,
			'fingerprints_removed'      => $request->fingerprints_removed,
			'floor_swept'               => $request->floor_swept,
			'waste_removed'             => $request->waste_removed,
			'hoarding_type'             => $request->hoarding_type,
			'installed_per_plan'        => $request->installed_per_plan,
			'set_out'                   => $request->set_out,
			'uprights_installed'        => $request->uprights_installed,
			'stud_spec'                 => $request->stud_spec,
			'double_stud'               => $request->double_stud,
			'panel_installed'           => $request->panel_installed,
			'screw_size'                => $request->screw_size,
			'panel_fixing'              => $request->panel_fixing,
			'counterweights_quantity'   => $request->counterweights_quantity,
			'counterweights_height'     => $request->counterweights_height,
			'wind_compliant'            => $request->wind_compliant,
			'returns'                   => $request->returns,
			'bracing'                   => $request->bracing,
			'certificate'               => $request->certificate,
		]);

		$job->status = 'pending-review';
		$job->save();
	}

	/**
	 * Endpoint to allow installers to sign the form.
	 */
	public function postSign(Request $request, $job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		// Determine the user to sign as
		if (isset($request->user_id)) {
			$user = User::find($request->user_id);

			if (!$user || !Hash::check($request->password, $user->password)) {
				throw new ServiceValidationException('Invalid password.', 'password');
			}
		} else {
			$user = Auth::guard('api')->user();
		}

		// if ($job->jsra->has_public_access == '') {
		// 	throw new ServiceValidationException('Can\'t sign because the JSRA form is not complete.');
		// }

		DB::table('job_installers')
			->where('job_id', $job->id)
			->where('installer_id', $user->id)
			->update(['form_signed_at' => Carbon::now()]);

		$job->updateFormStatus();

		$signed_installers_id = [];

		foreach ($job->installers as $job_installer) {
			if ($job_installer->form_signed_at) {
				$signed_installers_id[] = $job_installer->installer_id;
			}
		}

		return response()->json([
			'signed_installers' => $signed_installers_id,
			'job_status' => $job->status,
		]);
	}

}
