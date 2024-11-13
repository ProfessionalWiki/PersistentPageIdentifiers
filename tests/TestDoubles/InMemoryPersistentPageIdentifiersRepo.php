<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\TestDoubles;

use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;

class InMemoryPersistentPageIdentifiersRepo implements PersistentPageIdentifiersRepo {

	private array $persistentIds = [];

	public function savePersistentId( int $pageId, string $persistentId ): void {
		$this->persistentIds[$pageId] = $persistentId;
	}

	public function getPersistentId( int $pageId ): ?string {
		return $this->persistentIds[$pageId] ?? null;
	}

}
