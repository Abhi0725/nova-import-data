<?php

namespace Squareboat\ImportData;

use Illuminate\Contracts\Validation\Factory;
use Laravel\Nova\Actions\Action;

/**
 * Imports the uploaded data which was extracted by the.
 */

class ImportHandler {
	/**
	 * Uploaded data.
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Imports the uploaded data.
	 *
	 * @param array $data
	 */
	public function __construct(array $data) {
		$this->data = $data;
	}

	/**
	 * Handles the data import.
	 *
	 * @param $model
	 * @return string|null error message
	 */
	public function handle($resource) {
		$data = $this->data;

		return Action::message(__('Import successful'));
	}

	public function getValidationFactory() {
		return app(Factory::class );
	}
}
