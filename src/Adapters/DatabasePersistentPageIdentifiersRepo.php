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

	public function savePersistentIds( array $ids ): void {
		$this->database->insert(
			'persistent_page_ids',
			array_map(
				fn( $pageId ) => [ 'page_id' => $pageId, 'persistent_id' => $ids[$pageId] ],
				array_keys( $ids )
			)
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
