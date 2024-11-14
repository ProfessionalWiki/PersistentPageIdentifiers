<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers;

use MediaWiki\MediaWikiServices;
use ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\PersistentPageIdFunction;
use ProfessionalWiki\PersistentPageIdentifiers\Infrastructure\IdGenerator;
use ProfessionalWiki\PersistentPageIdentifiers\Infrastructure\UuidGenerator;
use Wikimedia\Rdbms\IDatabase;

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

	public function getPersistentPageIdentifiersRepo(): PersistentPageIdentifiersRepo {
		return new DatabasePersistentPageIdentifiersRepo(
			$this->getDatabase()
		);
	}

	private function getDatabase(): IDatabase {
		return MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection( DB_PRIMARY );
	}

	public function newPersistentPageIdFunction(): PersistentPageIdFunction {
		return new PersistentPageIdFunction(
			$this->getPersistentPageIdentifiersRepo()
		);
	}

}
