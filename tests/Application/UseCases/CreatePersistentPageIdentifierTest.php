<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Application\UseCases;

use PHPUnit\Framework\TestCase;
use ProfessionalWiki\PersistentPageIdentifiers\Application\UseCases\CreatePersistentPageIdentifier;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\TestDoubles\InMemoryPersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\TestDoubles\StubIdGenerator;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\Application\UseCases\CreatePersistentPageIdentifier
 */
class CreatePersistentPageIdentifierTest extends TestCase {

	public function testCreatesIdentifier(): void {
		$pageId = 42;
		$persistentId = 'foo';

		$repo = new InMemoryPersistentPageIdentifiersRepo();
		$useCase = new CreatePersistentPageIdentifier( $repo, new StubIdGenerator( $persistentId ) );

		$useCase->createId( $pageId );

		$this->assertSame(
			$persistentId,
			$repo->getPersistentId( $pageId )
		);
	}

}
