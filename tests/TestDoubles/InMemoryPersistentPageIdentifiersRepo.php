<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\TestDoubles;

use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;

class InMemoryPersistentPageIdentifiersRepo implements PersistentPageIdentifiersRepo {

	private array $persistentIdsByPageId = [];

	public function savePersistentIds( array $ids ): void {
		$this->persistentIdsByPageId = $this->persistentIdsByPageId + $ids;
	}

	public function getPersistentId( int $pageId ): ?string {
		return $this->persistentIdsByPageId[$pageId] ?? null;
	}

	public function getPersistentIds( array $pageIds ): array {
		return array_intersect_key( $this->persistentIdsByPageId, array_flip( $pageIds ) );
	}

}
