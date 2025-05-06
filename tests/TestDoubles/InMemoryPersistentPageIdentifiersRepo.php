<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\TestDoubles;

use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;

class InMemoryPersistentPageIdentifiersRepo implements PersistentPageIdentifiersRepo {

	private array $persistentIds = [];

	public function savePersistentIds( array $ids ): void {
		$this->persistentIds = $this->persistentIds + $ids;
	}

	public function getPersistentId( int $pageId ): ?string {
		return $this->persistentIds[$pageId] ?? null;
	}

	public function getPersistentIds( array $pageIds ): array {
		return array_intersect_key( $this->persistentIds, array_flip( $pageIds ) );
	}

	public function getPageIdFromPersistentId( string $persistentId ): ?int {
		$pageId = array_search( $persistentId, $this->persistentIds, true );
		return $pageId === false ? null : $pageId;
	}

}
