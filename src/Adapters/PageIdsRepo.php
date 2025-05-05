<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Adapters;

use Wikimedia\Rdbms\IDatabase;

class PageIdsRepo {

	public function __construct(
		private readonly IDatabase $database
	) {
	}

	/**
	 * @return int[]
	 */
	public function getPageIdsOfPagesWithoutPersistentIds( int $limit ): array {
		return array_map(
			fn( $field ) => (int)$field,
			$this->database->newSelectQueryBuilder()
				->select( 'p.page_id' )
				->from( 'page', 'p' )
				->leftJoin( 'persistent_page_ids', 'ppi', 'p.page_id = ppi.page_id' )
				->where( 'ppi.page_id IS NULL' )
				->orderBy( 'p.page_id' )
				->limit( $limit )
				->fetchFieldValues()
		);
	}

	public function getPageIdFromPersistentId( string $persistentId ): ?int {
		$row = $this->database->newSelectQueryBuilder()
			->select( 'p.page_id' )
			->from( 'page', 'p' )
			->join( 'persistent_page_ids', 'ppi', 'p.page_id = ppi.page_id' )
			->where( [ 'ppi.persistent_id' => $persistentId ] )
			->fetchRow();

		return $row && is_object( $row ) ? (int)$row->page_id : null;
	}

}
