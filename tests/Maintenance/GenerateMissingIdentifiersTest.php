<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Maintenance;

use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;
use ProfessionalWiki\PersistentPageIdentifiers\Maintenance\GenerateMissingIdentifiers;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\Maintenance\GenerateMissingIdentifiers
 * @group Database
 */
class GenerateMissingIdentifiersTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass(): string {
		return GenerateMissingIdentifiers::class;
	}

	// TODO
	public function testRuns() {
		$this->maintenance->execute();

		$this->expectOutputRegex( '/Generated/' );
	}

}
