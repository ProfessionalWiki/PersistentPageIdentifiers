<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Adapters;

use Wikimedia\Rdbms\IDatabase;

class PageIdsRepo {

	public function __construct(
		private readonly IDatabase $database
	) {
	}

	public function getPageIdsOfPagesWithoutPersistentIds(): array {
		return $this->database->newSelectQueryBuilder()
			->select( 'p.page_id' )
			->from( 'page', 'p' )
			->leftJoin( 'persistent_page_ids', 'ppi', 'p.page_id = ppi.page_id' )
			->where( 'ppi.page_id IS NULL' )
			->fetchFieldValues();
	}

}
