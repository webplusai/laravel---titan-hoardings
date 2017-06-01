<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Image;

class TestJobsSeeder extends Seeder
{
	// For testing purpose only.

	public function run()
	{
		// Generates Test jobs for existing agent admins, Add image for testing.
		// Get test agent-admin
		$agent_admins = App\User::where('type', 'agent-admin')->get();

		foreach ($agent_admins as $agent_admin) {
			$agent = $agent_admin->agent;
			$clients = App\Client::where('agent_id', $agent->id)->get();
			foreach ($clients as $client) {
				// Create job.
				$job = factory(App\Job::class)->create([
					'agent_id'  => $agent->id,
					'client_id' => $client->id,
					'user_id'   => $agent_admin->id,
				]);

				$job->jsra()->create([]);
				$job->qc()->create([]);

				$count_image = 3;

				// Add images.
				for ($i = 1; $i < $count_image; $i++) {
					$stub = __DIR__ . '/../../tests/test_stubs/test.jpg';
					$filename = $job->id . '-' . substr(md5(microtime()), 0, 10);
					copy($stub, __DIR__ . '/../../public/storage/job-images/' . $filename . '.jpg');

					$image = InterventionImage::make($stub);
					$image->resize(50, null, function ($constraint) {
						$constraint->aspectRatio();
					});

					$image->save(__DIR__ . '/../../public/storage/job-images/' . $filename . '-thumbnail.jpg');

					$image = new Image();
					$image->user_id = $agent->installers->first()->id;
					$image->job_id = $job->id;
					$image->filename = $filename;
					$image->extension = 'jpg';
					$image->caption = 'TestCaption';
					$image->type = 'pre-installation';
					$image->save();
				}
			}
		}
	}

}
