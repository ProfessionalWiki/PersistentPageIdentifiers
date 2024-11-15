<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Integration;

use ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\PersistentPageIdentifiersIntegrationTest;
use WikiPage;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\PersistentPageIdentifiersHooks::onRevisionUndeleted
 * @group Database
 */
class PageUndeleteIntegrationTest extends PersistentPageIdentifiersIntegrationTest {

	private PersistentPageIdentifiersRepo $repo;

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'persistent_page_ids';
		$this->repo = new DatabasePersistentPageIdentifiersRepo( $this->db );
	}

	public function testUndeletedPageWithOldPageIdHasSamePersistentId(): void {
		$page = $this->createPageWithText();
		$pageId = $page->getId();
		$persistentId = $this->repo->getPersistentId( $pageId );

		$this->deletePage( $page );
		$this->undeletePage( $page );

		$this->assertSame( $pageId, $page->getId() );
		$this->assertSame( $persistentId, $this->repo->getPersistentId( $pageId ) );
	}

	private function undeletePage( WikiPage $page ): void {
		$this->getServiceContainer()->getUndeletePageFactory()
			->newUndeletePage( $page, $this->getTestUser()->getAuthority() )
			->undeleteUnsafe( 'test undelete' );
	}

}
