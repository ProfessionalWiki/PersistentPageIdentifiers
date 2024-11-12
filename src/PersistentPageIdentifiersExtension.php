<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers;

use ProfessionalWiki\PersistentPageIdentifiers\Infrastructure\IdGenerator;
use ProfessionalWiki\PersistentPageIdentifiers\Infrastructure\UuidGenerator;
use ProfessionalWiki\PersistentPageIdentifiers\Persistence\PersistentPageIdSaver;

class PersistentPageIdentifiersExtension {

	public const PERSISTENT_PAGE_ID_PROPERTY = 'persistent_page_id';

	public static function getInstance(): self {
		/** @var ?PersistentPageIdentifiersExtension $instance */
		static $instance = null;
		$instance ??= new self();
		return $instance;
	}

	public function getIdGenerator(): IdGenerator {
		return new UuidGenerator();
	}

	public function getPersistentPageIdSaver(): PersistentPageIdSaver {
		return new PersistentPageIdSaver();
	}

}
