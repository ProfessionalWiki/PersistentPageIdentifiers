<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Adapters;

use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;

class StubPersistentPageIdentifiersRepo implements PersistentPageIdentifiersRepo {

	public function __construct(
		private readonly ?string $id
	) {
	}

	public function savePersistentIds( array $ids ): void {
		// Do nothing.
	}

	public function getPersistentId( int $pageId ): ?string {
		return $this->id;
	}

	public function getPersistentIds( array $pageIds ): array {
		return array_combine( $pageIds, array_fill( 0, count( $pageIds ), $this->id ) );
	}

}
