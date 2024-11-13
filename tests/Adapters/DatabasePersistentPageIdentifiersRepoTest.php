<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Adapters;

use MediaWikiIntegrationTestCase;
use ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use Wikimedia\Rdbms\DBQueryError;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo
 * @group Database
 */
class DatabasePersistentPageIdentifiersRepoTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'persistent_page_ids';
	}

	private function newRepo(): PersistentPageIdentifiersRepo {
		return new DatabasePersistentPageIdentifiersRepo( $this->db );
	}

	public function testGetPersistentIdReturnsNullForPageWithoutPersistentId(): void {
		$repo = $this->newRepo();

		$this->assertNull( $repo->getPersistentId( 404 ) );
	}

	public function testCanSaveAndRetrievePersistentId(): void {
		$repo = $this->newRepo();
		$pageId = 42;
		$persistentId = '00000000-0000-0000-0000-000000000042';

		$repo->savePersistentId( $pageId, $persistentId );

		$this->assertSame( $persistentId, $repo->getPersistentId( $pageId ) );
	}

	public function testSetPersistentIdThrowsExceptionOnDuplicatePageId(): void {
		$repo = $this->newRepo();
		$pageId = 1;

		$repo->savePersistentId( $pageId, '00000000-0000-0000-0000-000000000010' );

		$this->expectException( DBQueryError::class );
		$repo->savePersistentId( $pageId, '00000000-0000-0000-0000-000000000020' );
	}

	public function testSetPersistentIdThrowsExceptionOnDuplicatePersistentId(): void {
		$repo = $this->newRepo();
		$persistentId = '00000000-0000-0000-0000-000000000001';

		$repo->savePersistentId( 1, $persistentId );

		$this->expectException( DBQueryError::class );
		$repo->savePersistentId( 2, $persistentId );
	}

}
