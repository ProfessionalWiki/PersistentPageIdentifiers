<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Adapters;

use ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\PersistentPageIdentifiersIntegrationTest;
use Wikimedia\Rdbms\DBError;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo
 * @group Database
 */
class DatabasePersistentPageIdentifiersRepoTest extends PersistentPageIdentifiersIntegrationTest {

	private PersistentPageIdentifiersRepo $repo;

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'persistent_page_ids';
		$this->repo = new DatabasePersistentPageIdentifiersRepo( $this->db );
		$this->disablePageSaveHook();
	}

	public function testGetPersistentIdReturnsNullForPageWithoutPersistentId(): void {
		$this->assertNull( $this->repo->getPersistentId( 404 ) );
	}

	public function testCanSaveAndRetrievePersistentId(): void {
		$pageId = $this->createPageWithText()->getId();
		$persistentId = '00000000-0000-0000-0000-000000000042';

		$this->repo->savePersistentIds( [ $pageId => $persistentId ] );

		$this->assertSame( $persistentId, $this->repo->getPersistentId( $pageId ) );
	}

	public function testSetPersistentIdThrowsExceptionOnDuplicatePageId(): void {
		$pageId = $this->createPageWithText()->getId();

		$this->repo->savePersistentIds( [ $pageId => '00000000-0000-0000-0000-000000000010' ] );

		$this->expectException( DBError::class );
		$this->repo->savePersistentIds( [ $pageId => '00000000-0000-0000-0000-000000000020' ] );
	}

	public function testSetPersistentIdThrowsExceptionOnDuplicatePersistentId(): void {
		$persistentId = '00000000-0000-0000-0000-000000000001';
		$pageId1 = $this->createPageWithText()->getId();
		$pageId2 = $this->createPageWithText()->getId();

		$this->repo->savePersistentIds( [ $pageId1 => $persistentId ] );

		$this->expectException( DBError::class );
		$this->repo->savePersistentIds( [ $pageId2 => $persistentId ] );
	}

	public function testGetPersistentIdsReturnsNothingForNonExistentPage(): void {
		$this->assertSame( [], $this->repo->getPersistentIds( [ 404 ] ) );
	}

	public function testGetPersistentIdsReturnsNothingForDeletedPage(): void {
		$page = $this->createPageWithText();

		$this->repo->savePersistentIds( [
			$page->getId() => '00000000-0000-0000-0000-000000000042'
		] );

		$this->deletePage( $page );

		$this->assertSame( [], $this->repo->getPersistentIds( [ $page->getId() ] ) );
	}

	public function testGetPersistentIdsReturnsNullForPageWithoutPersistentId(): void {
		$pageId = $this->createPageWithText()->getId();

		$this->assertSame( [ $pageId => null ], $this->repo->getPersistentIds( [ $pageId ] ) );
	}

	public function testCanSaveAndRetrieveMultiplePersistentIds(): void {
		$pageId1 = $this->createPageWithText()->getId();
		$pageId2 = $this->createPageWithText()->getId();
		$pageId3 = $this->createPageWithText()->getId();
		$persistentId1 = '00000000-0000-0000-0000-000000000042';
		$persistentId3 = '00000000-0000-0000-0000-000000000043';

		$this->repo->savePersistentIds( [
			$pageId1 => $persistentId1,
			$pageId3 => $persistentId3
		] );

		$this->assertSame(
			[
				$pageId1 => $persistentId1,
				$pageId2 => null,
				$pageId3 => $persistentId3,
			],
			$this->repo->getPersistentIds( [ $pageId1, $pageId2, $pageId3, 404 ] )
		);
	}

	public function testGetPageIdFromPersistentId(): void {
		$pageId = $this->createPageWithText()->getId();
		$persistentId = '00000000-0000-0000-0000-000000000042';

		$this->repo->savePersistentIds( [ $pageId => $persistentId ] );
		$this->assertSame( $pageId, $this->repo->getPageIdFromPersistentId( $persistentId ) );
	}

	public function testGetPageIdFromNonExistentPersistentId(): void {
		$this->assertNull( $this->repo->getPageIdFromPersistentId( 'non-existent' ) );
	}

}
