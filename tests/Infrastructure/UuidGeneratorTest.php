<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Tests;

use PHPUnit\Framework\TestCase;
use ProfessionalWiki\PersistentPageIdentifiers\Infrastructure\UuidGenerator;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\Infrastructure\UuidGenerator
 */
class UuidGeneratorTest extends TestCase {

	public function testGeneratesValidUuid7(): void {
		$this->assertRegExp(
			'/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
			( new UuidGenerator() )->generate()
		);
	}

}
