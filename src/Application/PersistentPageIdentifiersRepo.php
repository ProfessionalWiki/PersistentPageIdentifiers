<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Application;

interface PersistentPageIdentifiersRepo {

	public function savePersistentId( int $pageId, string $persistentId ): void;

	public function getPersistentId( int $pageId ): ?string;

}
