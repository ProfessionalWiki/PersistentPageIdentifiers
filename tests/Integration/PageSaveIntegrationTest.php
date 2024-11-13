<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Integration;

use ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\PersistentPageIdentifiersHooks;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\PersistentPageIdentifiersIntegrationTest;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\PersistentPageIdentifiersHooks::onPageSaveComplete
 * @group Database
 */
class PageSaveIntegrationTest extends PersistentPageIdentifiersIntegrationTest {

	private PersistentPageIdentifiersRepo $repo;

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'persistent_page_ids';
		$this->repo = new DatabasePersistentPageIdentifiersRepo( $this->db );
	}

	public function testNewPageGetsPersistentId(): void {
		$page = $this->createPageWithText();

		$this->assertNotNull( $this->repo->getPersistentId( $page->getId() ) );
	}

	public function testPersistentIdDoesNotChange(): void {
		$page = $this->createPageWithText();
		$id = $this->repo->getPersistentId( $page->getId() );

		$this->editPage( $page, 'Updated' );

		$this->assertSame( $id, $this->repo->getPersistentId( $page->getId() ) );
	}

	public function testExistingPageDoesNotGetPersistentId(): void {
		$this->clearHook( 'PageSaveComplete' );
		$page = $this->createPageWithText();

		$this->assertNull( $this->repo->getPersistentId( $page->getId() ) );

		$this->setTemporaryHook( 'PageSaveComplete', [ PersistentPageIdentifiersHooks::class, 'onPageSaveComplete' ] );
		$this->editPage( $page, 'Updated' );

		$this->assertNull( $this->repo->getPersistentId( $page->getId() ) );
	}

}
