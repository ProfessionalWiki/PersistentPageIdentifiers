<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers;

use ProfessionalWiki\PersistentPageIdentifiers\Infrastructure\IdGenerator;
use ProfessionalWiki\PersistentPageIdentifiers\Infrastructure\UuidGenerator;

class PersistentPageIdentifiersExtension {

	public static function getInstance(): self {
		/** @var ?PersistentPageIdentifiersExtension $instance */
		static $instance = null;
		$instance ??= new self();
		return $instance;
	}

	public function getIdGenerator(): IdGenerator {
		return new UuidGenerator();
	}

}
