<?php

namespace Squareboat\ImportData;

use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Http\Requests\NovaRequest;

class ImportController {
	public function handle(NovaRequest $request) {
		$resource   = $request->newResource();
		$fileReader = CsvFileReader::class ;

		$data = Validator::make($request->all(), [
				'file' => 'required|file|mimes:'.$fileReader::mimes(),
			])->validate();

		$file = $request->file('file');

		try {
			$fileReader = new $fileReader($file);
			$data       = $fileReader->readFile();
		} catch (\Exception $e) {
			return Action::danger(__('An error occurred during the import'));
		}

		return $resource->importData($data, $file);
	}
}
