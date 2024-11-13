<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests;

use Title;
use WikiPage;

class PersistentPageIdentifiersIntegrationTest extends \MediaWikiIntegrationTestCase {

	protected function createPageWithText( string $text = 'Whatever wikitext' ): WikiPage {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $this->createUniqueTitle() );

		$this->editPage( $page, $text );

		return $page;
	}

	private function createUniqueTitle(): Title {
		static $pageCounter = 0;
		return Title::newFromText( 'PPITestPage' . ++$pageCounter );
	}

}
