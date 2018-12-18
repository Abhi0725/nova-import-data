<?php

namespace Squareboat\ImportData;

use Laravel\Nova\Card;
use Laravel\Nova\Fields\File;

class ImportData extends Card {
	/**
	 * The width of the card (1/3, 1/2, or full).
	 *
	 * @var string
	 */
	public $width = 'full';

	public function __construct($resource) {
		parent::__construct();
		$this->withMeta([
				'fields' => [
					new File('File'),
				],
				'resourceLabel' => $resource::label(),
				'resource'      => $resource::uriKey(),
			]);
	}

	/**
	 * Get the component name for the element.
	 *
	 * @return string
	 */
	public function component() {
		return 'ImportData';
	}
}
