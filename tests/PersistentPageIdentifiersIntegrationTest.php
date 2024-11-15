<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests;

use ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\PersistentPageIdentifiersHooks;
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

	protected function disablePageSaveHook(): void {
		$this->clearHook( 'PageSaveComplete' );

	}

	protected function disableParserFunction(): void {
		$parser = $this->getServiceContainer()->getParser();
		$parser->setFunctionHook( 'ppid', static function () {
			return [ '', 'noparse' => true ];
		} );
	}

	protected function enablePageSaveHook(): void {
		$this->setTemporaryHook( 'PageSaveComplete', [ PersistentPageIdentifiersHooks::class, 'onPageSaveComplete' ] );
	}

}
