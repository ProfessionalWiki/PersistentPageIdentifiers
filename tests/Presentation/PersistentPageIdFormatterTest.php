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
		$formatter = new PersistentPageIdFormatter( '$1' );
		$this->assertSame( '', $formatter->format( null ) );
	}

	public function testReturnsFormattedId(): void {
		$formatter = new PersistentPageIdFormatter( 'foo $1 bar' );
		$this->assertSame( 'foo 42 bar', $formatter->format( '42' ) );
	}

	public function testEscapesHtmlInFormat(): void {
		$formatter = new PersistentPageIdFormatter( '<strong>$1</strong>' );
		$this->assertSame( '&lt;strong&gt;42&lt;/strong&gt;', $formatter->format( '42' ) );
	}

	public function testEscapesHtmlInId(): void {
		$formatter = new PersistentPageIdFormatter( '$1' );
		$this->assertSame( '&lt;strong&gt;42&lt;/strong&gt;', $formatter->format( '<strong>42</strong>' ) );
	}

}
