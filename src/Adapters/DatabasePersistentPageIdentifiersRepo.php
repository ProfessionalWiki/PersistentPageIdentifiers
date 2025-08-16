<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Adapters;

use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

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
		return $this->getPersistentIds( [ $pageId ] )[$pageId] ?? null;
	}

	/**
	 * @param int[] $pageIds
	 * @return array<int, string|null>
	 */
	public function getPersistentIds( array $pageIds ): array {
		$result = $this->database->newSelectQueryBuilder()
			->select( [ 'p.page_id', 'ppi.persistent_id' ] )
			->from( 'page', 'p' )
			// Join is necessary to exclude pages deleted from the `page` table, but not `persistent_page_ids`.
			->leftJoin( 'persistent_page_ids', 'ppi', 'p.page_id = ppi.page_id' )
			->where( [ 'p.page_id' => $pageIds ] )
			->orderBy( 'p.page_id' )
			->caller( __METHOD__ )
			->fetchResultSet();

		return $this->persistentIdsResultToArray( $result );
	}

	/**
	 * @return array<int, string|null>
	 */
	private function persistentIdsResultToArray( IResultWrapper $result ): array {
		$rows = [];

		foreach ( $result as $row ) {
			$rows[(int)$row->page_id] = $row->persistent_id;
		}

		return $rows;
	}

	public function getPageIdFromPersistentId( string $persistentId ): ?int {
		$pageId = $this->database->newSelectQueryBuilder()
			->select( 'page_id' )
			->from( 'persistent_page_ids' )
			->where( [ 'persistent_id' => $persistentId ] )
			->fetchField();

		return is_numeric( $pageId ) ? (int)$pageId : null;
	}

}
