<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Integration;

use ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\PersistentPageIdentifiersIntegrationTest;

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
	}

	public function testParserFunctionReturnsPersistentId(): void {
		$page = $this->createPageWithText();
		$this->editPage( $page, '{{#ppid:}}' );
		$id = $this->repo->getPersistentId( $page->getId() );

		$this->assertSame(
			<<<HTML
<p>$id
</p>
HTML
			,
			$page->getParserOutput()->getText( [ 'unwrap' => true ] )
		);
	}

	public function testParserFunctionReturnsNothingForPageWithoutPersistentId(): void {
		$this->clearHook( 'PageSaveComplete' );

		$page = $this->createPageWithText();
		$this->editPage( $page, '{{#ppid:}}' );

		$this->assertSame(
			'',
			$page->getParserOutput()->getText( [ 'unwrap' => true ] )
		);
	}

}
