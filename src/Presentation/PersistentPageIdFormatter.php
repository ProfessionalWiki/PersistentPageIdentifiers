<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Presentation;

class PersistentPageIdFormatter {

	public function __construct(
		private readonly string $format
	) {
	}

	public function format( ?string $persistentId ): string {
		if ( $persistentId === null ) {
			return '';
		}

		return htmlspecialchars(
			str_replace( '$1', $persistentId, $this->format )
		);
	}

}
