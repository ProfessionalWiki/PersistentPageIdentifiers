<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests;

use PHPUnit\Framework\TestCase;
use ProfessionalWiki\PersistentPageIdentifiers\PersistentPageIdentifiersExtension;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\PersistentPageIdentifiersExtension
 */
class PersistentPageIdentifiersExtensionTest extends TestCase {

	public function testGetInstanceIsSingleton(): void {
		$this->assertSame( PersistentPageIdentifiersExtension::getInstance(), PersistentPageIdentifiersExtension::getInstance() );
	}

}
