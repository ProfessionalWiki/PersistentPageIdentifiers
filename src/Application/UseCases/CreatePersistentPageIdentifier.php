<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Application\UseCases;

use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Infrastructure\IdGenerator;

class CreatePersistentPageIdentifier {

	public function __construct(
		private readonly PersistentPageIdentifiersRepo $repo,
		private readonly IdGenerator $idGenerator
	) {
	}

	public function createId( int $pageId ): void {
		$this->repo->savePersistentId( $pageId, $this->idGenerator->generate() );
	}

}
