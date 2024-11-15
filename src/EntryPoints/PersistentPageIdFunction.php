<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use Parser;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Presentation\PersistentPageIdFormatter;
use Title;
use WikiPage;

class PersistentPageIdFunction {

	public function __construct(
		private readonly PersistentPageIdentifiersRepo $repo, private readonly PersistentPageIdFormatter $idFormatter,
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
			htmlspecialchars( $this->idFormatter->format( $this->getPersistentIdForPage( $page ) ) ),
			'noparse' => true,
			'isHTML' => false,
		];
	}

	private function getPersistentIdForPage( PageReference $page ): ?string {
		return $this->repo->getPersistentId( Title::castFromPageReference( $page )->getArticleID() );
	}

}
