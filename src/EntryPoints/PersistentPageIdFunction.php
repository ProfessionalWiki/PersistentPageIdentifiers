<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use Parser;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\PersistentPageIdentifiersExtension;
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

		$title = Title::castFromPageReference( $page );
		$id = $this->getPersistentIdForPage( $page );

		if ( $id === null && $title->exists() ) {
			$pageId = $title->getArticleID();
			$id = PersistentPageIdentifiersExtension::getInstance()->getIdGenerator()->generate();
			$this->repo->savePersistentIds( [ $pageId => $id ] );
		}

		return [
			htmlspecialchars( $this->idFormatter->format( $id ) ),
			'noparse' => true,
			'isHTML' => false,
		];
	}

	private function getPersistentIdForPage( PageReference $page ): ?string {
		return $this->repo->getPersistentId( Title::castFromPageReference( $page )->getArticleID() );
	}

}
