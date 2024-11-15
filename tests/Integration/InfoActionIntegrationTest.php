<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Integration;

use IContextSource;
use ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\PersistentPageIdentifiersHooks;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\PersistentPageIdentifiersIntegrationTest;
use RequestContext;
use WikiPage;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\PersistentPageIdentifiersHooks::onInfoAction
 * @group Database
 */
class InfoActionIntegrationTest extends PersistentPageIdentifiersIntegrationTest {

	private PersistentPageIdentifiersRepo $repo;

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'persistent_page_ids';
		$this->repo = new DatabasePersistentPageIdentifiersRepo( $this->db );
	}

	public function testShowsEmptyStringForPageWithoutPersistentId(): void {
		$this->clearHook( 'RevisionFromEditComplete' );

		$page = $this->createPageWithText();

		$pageInfo = [];
		PersistentPageIdentifiersHooks::onInfoAction( $this->newContextWithPage( $page ), $pageInfo );

		$this->assertInfoHasPersistentId( '', $pageInfo );
	}

	private function newContextWithPage( WikiPage $page ): IContextSource {
		$context = RequestContext::getMain();
		$context->setWikiPage( $page );
		return $context;
	}

	private function assertInfoHasPersistentId( ?string $expected, array $pageInfo ): void {
		$this->assertSame( $expected, $pageInfo['header-basic'][0][1] );
	}

	public function testShowsFormattedIdForPageWithPersistentId(): void {
		$this->overrideConfigValue( 'PersistentPageIdentifiersFormat', 'foo/$1/bar' );

		$page = $this->createPageWithText();

		$pageInfo = [];
		PersistentPageIdentifiersHooks::onInfoAction( $this->newContextWithPage( $page ), $pageInfo );

		$id = $this->repo->getPersistentId( $page->getId() );
		$this->assertInfoHasPersistentId( "foo/$id/bar", $pageInfo );
	}

	public function testEscapesFormattedPersistentId(): void {
		$this->overrideConfigValue( 'PersistentPageIdentifiersFormat', '<strong>$1<script>alert(42)</script>' );

		$page = $this->createPageWithText();

		$pageInfo = [];
		PersistentPageIdentifiersHooks::onInfoAction( $this->newContextWithPage( $page ), $pageInfo );

		$id = $this->repo->getPersistentId( $page->getId() );
		$this->assertInfoHasPersistentId( "&lt;strong&gt;$id&lt;script&gt;alert(42)&lt;/script&gt;", $pageInfo );
	}

}
