<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Presentation;

use PHPUnit\Framework\TestCase;
use ProfessionalWiki\PersistentPageIdentifiers\Presentation\PersistentPageIdFormatter;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\Presentation\PersistentPageIdFormatter
 */
class PersistentPageIdFormatterTest extends TestCase {

	public function testReturnsEmptyStringForNullId(): void {
		$formatter = new PersistentPageIdFormatter( 'foo $1 bar' );
		$this->assertSame( '', $formatter->format( null ) );
	}

	public function testReturnsFormattedId(): void {
		$formatter = new PersistentPageIdFormatter( 'foo $1 bar' );
		$this->assertSame( 'foo 42 bar', $formatter->format( '42' ) );
	}

}
