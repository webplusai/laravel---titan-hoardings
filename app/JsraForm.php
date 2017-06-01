<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JsraForm extends Model
{

	protected $table = 'jsra_forms';

	protected $guarded = [];

	public function getHandlingSelections()
	{
		if ($this->manual_handling != 'Y') {
			return [];
		}

		$selections = [];

		if ($this->multi_person_lift) {
			$selections[] = 'Multi-person lift for heavy or awkward items';
		}

		if ($this->trolleys_for_transport) {
			$selections[] = 'Trolleys for transport of materials & equipments';
		}

		if ($this->lift_one_at_time) {
			$selections[] = 'Lift one TITAN counterweight at a time';
		}

		if ($this->job_rotation_breaks) {
			$selections[] = 'Job rotation or breaks to limit repetitive lifting';
		}

		return $selections;
	}

	public function getPublicSelections()
	{
		if ($this->has_public_access != 'Y') {
			return [];
		}

		$selections = [];

		if ($this->exclusion_zone) {
			$selections[] = 'Exclusion zone delineated with hi-vis barriers';
		}

		if ($this->awareness_of_people) {
			$selections[] = 'Maintain awareness of unauthorised people';
		}

		return $selections;
	}

	public function getHeightSelections()
	{
		if ($this->is_above_2_metres != 'Y') {
			return [];
		}

		$selections = [];

		if ($this->platform_ladder) {
			$selections[] = 'Platform Ladder';
		}

		if ($this->ewp) {
			$selections[] = 'EWP';
		}

		if ($this->mobile_scaffold) {
			$selections[] = 'Mobile Scaffold';
		}

		return $selections;
	}

	public function getHazardSelections()
	{
		if ($this->hazardous_material != 'Y') {
			return [];
		}

		$selections = [];

		if ($this->vacuum_dust) {
			$selections[] = 'Vacuum dust extraction';
		}

		if ($this->respiratory_eye_hearing_ppe) {
			$selections[] = 'Wear respiratory, eye & hearing PPE';
		}

		return $selections;
	}

	public function getFallingSelections()
	{
		if ($this->has_potential_falling_objects != 'Y') {
			return [];
		}

		$selections = [];

		if ($this->two_persons) {
			$selections[] = 'Two persons preventing uncontrolled movement of each panel';
		}

		if ($this->wear_hardhat) {
			$selections[] = 'Wear hardhat PPE';
		}

		return $selections;
	}

	public function getPPESelections()
	{
		if ($this->wear_appropriate_ppe != 'Y') {
			return [];
		}

		$selections = [];

		if ($this->ppe_boots) {
			$selections[] = 'Safety Boots steel capped';
		}

		if ($this->ppe_shirt) {
			$selections[] = 'Hi-vis Shirt';
		}

		if ($this->ppe_eye_protection) {
			$selections[] = 'Safety spectacles';
		}

		if ($this->ppe_ears) {
			$selections[] = 'Earplugs/earmuffs';
		}

		if ($this->ppe_gloves) {
			$selections[] = 'Gloves';
		}

		return $selections;
	}

	public function getOtherSelections()
	{
		if ($this->has_other_hazards != 'Y') {
			return '';
		} else {
			return $this->other_hazards;
		}
	}
}