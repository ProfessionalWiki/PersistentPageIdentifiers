<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Adapters;

use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use Wikimedia\Rdbms\IDatabase;

class DatabasePersistentPageIdentifiersRepo implements PersistentPageIdentifiersRepo {

	public function __construct(
		private readonly IDatabase $database
	) {
	}

	public function savePersistentId( int $pageId, string $persistentId ): void {
		$this->database->insert(
			'persistent_page_ids',
			[
				'page_id' => $pageId,
				'persistent_id' => $persistentId
			]
		);
	}

	public function getPersistentId( int $pageId ): ?string {
		$field = $this->database->selectField(
			'persistent_page_ids',
			'persistent_id',
			[ 'page_id' => $pageId ]
		);

		return $field === false ? null : $field;
	}

}
