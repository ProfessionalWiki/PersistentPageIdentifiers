<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Application;

interface PersistentPageIdentifiersRepo {

	/**
	 * @param array<int, string> $ids
	 */
	public function savePersistentIds( array $ids ): void;

	public function getPersistentId( int $pageId ): ?string;

}
