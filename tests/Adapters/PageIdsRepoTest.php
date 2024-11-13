<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Adapters;

use ProfessionalWiki\PersistentPageIdentifiers\Adapters\PageIdsRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\PersistentPageIdentifiersIntegrationTest;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\Adapters\PageIdsRepo
 * @group Database
 */
class PageIdsRepoTest extends PersistentPageIdentifiersIntegrationTest {

	private PageIdsRepo $repo;

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'persistent_page_ids';
		$this->repo = new PageIdsRepo( $this->db );
	}

	public function testReturnsAnEmptyArrayWhenThereAreNoPages(): void {
		$this->assertSame( [], $this->repo->getPageIdsOfPagesWithoutPersistentIds() );
	}

	public function testReturnsAnEmptyArrayWhenThereAreNoPagesWithPersistentIds(): void {
		$this->createPageWithText();
		$this->createPageWithText();

		$this->assertSame( [], $this->repo->getPageIdsOfPagesWithoutPersistentIds() );
	}

	public function testReturnsPageIdsForPagesWithoutPersistentIds(): void {
		$this->disablePageSaveHook();
		$page1 = $this->createPageWithText();
		$page2 = $this->createPageWithText();

		$this->assertSame(
			[ (string)$page1->getId(), (string)$page2->getId() ],
			$this->repo->getPageIdsOfPagesWithoutPersistentIds()
		);
	}

	public function testReturnsPageIdsOnlyForPagesWithoutPersistentIds(): void {
		$this->disablePageSaveHook();
		$page1 = $this->createPageWithText();
		$page2 = $this->createPageWithText();

		$this->enablePageSaveHook();
		$this->createPageWithText();
		$this->createPageWithText();

		$this->assertSame(
			[ (string)$page1->getId(), (string)$page2->getId() ],
			$this->repo->getPageIdsOfPagesWithoutPersistentIds()
		);
	}

}
