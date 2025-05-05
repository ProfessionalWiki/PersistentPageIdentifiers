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
		$this->assertSame( [], $this->repo->getPageIdsOfPagesWithoutPersistentIds( limit: 100 ) );
	}

	public function testReturnsAnEmptyArrayWhenThereAreNoPagesWithPersistentIds(): void {
		$this->createPageWithText();
		$this->createPageWithText();

		$this->assertSame( [], $this->repo->getPageIdsOfPagesWithoutPersistentIds( limit: 100 ) );
	}

	public function testReturnsPageIdsForPagesWithoutPersistentIds(): void {
		$this->disablePageSaveHook();
		$page1 = $this->createPageWithText();
		$page2 = $this->createPageWithText();

		$this->assertSame(
			[ $page1->getId(), $page2->getId() ],
			$this->repo->getPageIdsOfPagesWithoutPersistentIds( limit: 100 )
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
			[ $page1->getId(), $page2->getId() ],
			$this->repo->getPageIdsOfPagesWithoutPersistentIds( limit: 100 )
		);
	}

	public function testReturnsPageIdsForPagesWithoutPersistentIdsUpToLimit(): void {
		$this->disablePageSaveHook();
		$page1 = $this->createPageWithText();
		$this->createPageWithText();
		$this->createPageWithText();

		$this->assertSame(
			[ $page1->getId() ],
			$this->repo->getPageIdsOfPagesWithoutPersistentIds( limit: 1 )
		);
	}

	public function testGetPageIdFromPersistentId(): void {
		$page = $this->createPageWithText();

		$persistentId = $this->db->newSelectQueryBuilder()
			->select( 'ppi.persistent_id' )
			->from( 'persistent_page_ids', 'ppi' )
			->where( [ 'ppi.page_id' => $page->getId() ] )
			->fetchField();

		$this->assertIsString( $persistentId );
		$this->assertSame( $page->getId(), $this->repo->getPageIdFromPersistentId( $persistentId ) );
	}

	public function testGetPageIdFromNonExistentPersistentId(): void {
		$this->assertNull( $this->repo->getPageIdFromPersistentId( 'non-existent' ) );
	}

}
