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

		try {
			$fileReader = new $fileReader($request->file('file'));
			$data       = $fileReader->readFile();
		} catch (\Exception $e) {
			Action::danger(__('An error occurred during the import'));
		}

		return $resource->importData($data);
	}

	/**
	 * @param $data
	 * @param NovaRequest $request
	 * @param $resource
	 */
	protected function validateFields($data, $request, $resource):void {
		$rules = collect($resource::rulesForCreation($request))->mapWithKeys(function ($rule, $key) {
				return ['*.'.$key => $rule];
			});
		Validator::make($data, $rules->toArray())->validate();
	}
}
