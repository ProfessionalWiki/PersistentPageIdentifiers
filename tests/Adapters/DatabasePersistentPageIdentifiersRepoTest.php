<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Adapters;

use MediaWikiIntegrationTestCase;
use ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use Wikimedia\Rdbms\DBError;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo
 * @group Database
 */
class DatabasePersistentPageIdentifiersRepoTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'page';
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

		$repo->savePersistentIds( [ $pageId => $persistentId ] );

		$this->assertSame( $persistentId, $repo->getPersistentId( $pageId ) );
	}

	public function testSetPersistentIdThrowsExceptionOnDuplicatePageId(): void {
		$repo = $this->newRepo();
		$pageId = 100;

		$repo->savePersistentIds( [ $pageId => '00000000-0000-0000-0000-000000000010' ] );

		$this->expectException( DBError::class );
		$repo->savePersistentIds( [ $pageId => '00000000-0000-0000-0000-000000000020' ] );
	}

	public function testSetPersistentIdThrowsExceptionOnDuplicatePersistentId(): void {
		$repo = $this->newRepo();
		$persistentId = '00000000-0000-0000-0000-000000000001';

		$repo->savePersistentIds( [ 100 => $persistentId ] );

		$this->expectException( DBError::class );
		$repo->savePersistentIds( [ 200 => $persistentId ] );
	}

}
