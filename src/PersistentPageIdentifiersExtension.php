<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers;

class PersistentPageIdentifiersExtension {

	public static function getInstance(): self {
		/** @var ?PersistentPageIdentifiersExtension $instance */
		static $instance = null;
		$instance ??= new self();
		return $instance;
	}

}
