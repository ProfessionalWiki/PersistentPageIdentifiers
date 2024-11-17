<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Maintenance;

use Maintenance;
use ProfessionalWiki\PersistentPageIdentifiers\Maintenance\GenerateMissingIdentifiers;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\PersistentPageIdentifiersIntegrationTest;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\Maintenance\GenerateMissingIdentifiers
 * @group Database
 */
class GenerateMissingIdentifiersTest extends PersistentPageIdentifiersIntegrationTest {

	private Maintenance $maintenance;

	protected function setUp(): void {
		parent::setUp();

		$this->maintenance = new GenerateMissingIdentifiers();
	}

	protected function tearDown(): void {
		$this->maintenance->cleanupChanneled();
		parent::tearDown();
	}

	public function testGeneratesNothingIfThereAreNoPages() {
		$this->maintenance->execute();

		$this->expectOutputRegex( '/Generated 0 persistent IDs/' );
	}

	public function testGeneratesNothingIfAllPagesHavePersistentIds() {
		$this->createPageWithText();
		$this->createPageWithText();

		$this->maintenance->execute();

		$this->expectOutputRegex( '/Generated 0 persistent IDs/' );
	}

	public function testGeneratesIdsIfThereArePagesWithoutPersistentIds() {
		$this->disablePageSaveHook();
		$this->createPageWithText();
		$this->createPageWithText();

		$this->maintenance->execute();

		$this->expectOutputRegex( '/Generated 2 persistent IDs/' );
	}

	public function testGeneratesSomeIdsIfThereAreSomePagesWithoutPersistentIds() {
		$this->createPageWithText();
		$this->createPageWithText();

		$this->disablePageSaveHook();
		$this->createPageWithText();

		$this->maintenance->execute();

		$this->expectOutputRegex( '/Generated 1 persistent IDs/' );
	}

}
