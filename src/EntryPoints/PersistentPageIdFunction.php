<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use MediaWiki\Page\PageReference;
use Parser;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use Title;

class PersistentPageIdFunction {

	public function __construct(
		private readonly PersistentPageIdentifiersRepo $repo
	) {
	}

	/**
	 * @return array<mixed, mixed>
	 */
	public function handleParserFunctionCall( Parser $parser ): array {
		$page = $parser->getPage();

		if ( $page === null ) {
			return [];
		}

		return [
			$this->repo->getPersistentId( $this->getPageId( $page ) ) ?? '',
			'noparse' => true,
			'isHTML' => false,
		];
	}

	private function getPageId( PageReference $page ): int {
		return Title::castFromPageReference( $page )->getArticleID();
	}

}
