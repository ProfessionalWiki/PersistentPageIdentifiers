<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Integration;

use ParserOutput;
use ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\PersistentPageIdentifiersIntegrationTest;
use WikiPage;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\PersistentPageIdFunction
 * @group Database
 */
class PersistentPageIdFunctionIntegrationTest extends PersistentPageIdentifiersIntegrationTest {

	private PersistentPageIdentifiersRepo $repo;

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'persistent_page_ids';
		$this->repo = new DatabasePersistentPageIdentifiersRepo( $this->db );
		$this->overrideConfigValue( 'PersistentPageIdentifiersFormat', '$1' );
	}

	public function testParserFunctionReturnsPersistentId(): void {
		$page = $this->createPageWithText();
		$this->editPage( $page, '{{#ppid:}}' );
		$id = $this->repo->getPersistentId( $page->getId() );

		$this->assertPageContentIs(
			<<<HTML
<p>$id
</p>
HTML
			,
			$page
		);
	}

	private function assertPageContentIs( string $expected, WikiPage $page ): void {
		$this->assertSame(
			$expected,
			$page->getParserOutput()->getText( [ 'unwrap' => true ] )
		);
	}

	public function testParserFunctionReturnsNothingForPageWithoutPersistentId(): void {
		$this->clearHook( 'PageSaveComplete' );

		$page = $this->createPageWithText();
		$this->editPage( $page, '{{#ppid:}}' );

		$this->assertPageContentIs(
			'',
			$page
		);
	}

	public function testParserFunctionReturnsPersistentIdWithCustomFormat(): void {
		$this->overrideConfigValue( 'PersistentPageIdentifiersFormat', 'foo/$1/bar' );

		$page = $this->createPageWithText();
		$this->editPage( $page, '{{#ppid:}}' );
		$id = $this->repo->getPersistentId( $page->getId() );

		$this->assertPageContentIs(
			<<<HTML
<p>foo/$id/bar
</p>
HTML
			,
			$page
		);
	}

}
