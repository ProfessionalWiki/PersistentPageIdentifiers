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

		return str_replace( '$1', $persistentId, $this->format );
	}

	public function extractId( string $input ): string {
		// \$1 because it is escaped in the format string
		$pattern = '/^' . str_replace( '\$1', '(.*?)', preg_quote( $this->format, '/' ) ) . '$/';
		return preg_match( $pattern, $input, $matches ) ? $matches[1] : $input;
	}

}
