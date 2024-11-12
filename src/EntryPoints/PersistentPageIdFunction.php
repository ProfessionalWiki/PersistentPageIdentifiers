<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use Parser;

class PersistentPageIdFunction {

	/**
	 * @return array<mixed, mixed>
	 */
	public function handleParserFunctionCall( Parser $parser ): array {
		return [
			'TODO',
			'noparse' => true,
			'isHTML' => false,
		];
	}

}
