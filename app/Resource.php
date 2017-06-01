<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;
use Storage;

class Resource extends Model
{

	/**
	 * Set the given file as the resource's file and set the url field.
	 *
	 * Applies to resources of type file and image only.
	 *
	 * Example url:
	 *     storage/resources/123.pdf
	 *
	 * If the resource is an image, a thumbnail is also created in the same
	 * folder named something like 123-thumbnail.png.
	 */
	public function setFile(UploadedFile $file)
	{
		if (!$this->id) {
			throw new InvalidArgumentException('You must call save() on a resource before setFile().');
		}

		if ($this->type != 'file' && $this->type != 'image') {
			throw new InvalidArgumentException('You can only use setFile() for resources of types file and image.');
		}

		if ($this->type == 'image' && substr($file->getMimeType(), 0, 5) != 'image') {
			throw new InvalidArgumentException('The file passed to setFile() on a resource of type image must be an image.');
		}

		$disk = Storage::disk('public');

		if ($this->url) {
			$this->deleteFile();
		}

		$basename = $this->id . '.' . $file->guessExtension();
		$disk->put('resources/' . $basename, file_get_contents($file));

		if ($this->type == 'image') {
			$image = Image::make(file_get_contents($file));
			$image->resize(50, null, function ($constraint) {
				$constraint->aspectRatio();
			});

			$basename_thumbnail = $this->id . '-thumbnail.' . $file->guessExtension();
			$disk->put('resources/' . $basename_thumbnail, $image->stream('jpg', 80));
		}

		$this->url = 'storage/resources/' . $basename;
		$this->save();
	}

	public function deleteFile()
	{
		if ($this->type != 'file' && $this->type != 'image') {
			throw new InvalidArgumentException('You can only use deleteFile() for resources of types file and image.');
		}

		if (!$this->url) {
			throw new InvalidArgumentException('No file to delete.');
		}

		$disk = Storage::disk('public');

		$basename = basename($this->url);
		$disk->delete('resources/' . $basename);

		$thumbnail_basename = preg_replace('/(\.[a-z]+)$/i', '-thumbnail$1', $basename);
		$disk->delete('resources/' . $thumbnail_basename);
	}

	/**
	 * Performs cleanup of relations and uploaded file before deleting the
	 * resource.
	 */
	public function delete()
	{
		$this->products()->detach();

		DB::table('resource_views')->whereResourceId($this->id)->delete();

		if (in_array($this->type, ['file', 'image']) && $this->url) {
			$this->deleteFile();
		}

		return parent::delete();
	}

	public function products()
	{
		return $this->belongsToMany('App\Product', 'product_resources');
	}

}
