<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\EntryPoints;

use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Response;
use MediaWiki\Tests\Rest\Handler\HandlerTestTrait;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\GetPersistentPageIdentifiersApi;
use ProfessionalWiki\PersistentPageIdentifiers\Presentation\PersistentPageIdFormatter;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\PersistentPageIdentifiersIntegrationTest;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\TestDoubles\InMemoryPersistentPageIdentifiersRepo;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\GetPersistentPageIdentifiersApi
 * @group Database
 */
class GetPersistentPageIdentifiersApiTest extends PersistentPageIdentifiersIntegrationTest {

	use HandlerTestTrait;

	private const PAGE_ID1 = 1;
	private const PAGE_ID2 = 42;
	private const PAGE_ID3 = 1337;
	private const PERSISTENT_ID1 = null;
	private const PERSISTENT_ID2 = 'id-42';
	private const PERSISTENT_ID3 = 'id-1337';
	private const PAGE_WITHOUT_ID1 = 100;
	private const PAGE_WITHOUT_ID2 = 101;

	private PersistentPageIdentifiersRepo $repo;

	protected function setUp(): void {
		parent::setUp();

		$this->repo = new InMemoryPersistentPageIdentifiersRepo();
		$this->repo->savePersistentIds( [
			self::PAGE_ID1 => self::PERSISTENT_ID1,
			self::PAGE_ID2 => self::PERSISTENT_ID2,
			self::PAGE_ID3 => self::PERSISTENT_ID3,
		] );
	}

	public function testReturnsArrayOfPersistentIdentifiers(): void {
		$response = $this->executeHandler(
			$this->newGetPersistentPageIdentifiersApi(),
			$this->createValidRequestData( [ self::PAGE_ID1, self::PAGE_WITHOUT_ID1, self::PAGE_ID3 ] )
		);

		$this->assertSame( 200, $response->getStatusCode() );
		$this->assertResponseHasIdentifiers(
			[
				self::PAGE_ID1 => '',
				self::PAGE_ID3 => self::PERSISTENT_ID3
			],
			$response
		);
	}

	private function newGetPersistentPageIdentifiersApi( string $format = '$1' ): GetPersistentPageIdentifiersApi {
		return new GetPersistentPageIdentifiersApi(
			$this->repo,
			new PersistentPageIdFormatter( $format )
		);
	}

	private function createValidRequestData( array $pageIds ): RequestData {
		return new RequestData( [
			'method' => 'GET',
			'queryParams' => [
				'ids' => implode( '|', $pageIds )
			]
		] );
	}

	private function assertResponseHasIdentifiers( array $expected, Response $response ): void {
		$json = json_decode( $response->getBody()->getContents(), true );
		$this->assertSame( $expected, $json['identifiers'] );
	}

	public function testReturnsEmptyArrayForNoMatchingPagesWithPersistentIdentifiers(): void {
		$response = $this->executeHandler(
			$this->newGetPersistentPageIdentifiersApi(),
			$this->createValidRequestData( [ self::PAGE_WITHOUT_ID1, self::PAGE_WITHOUT_ID2 ] )
		);

		$this->assertSame( 200, $response->getStatusCode() );
		$this->assertResponseHasIdentifiers(
			[],
			$response
		);
	}

	public function testReturnsArrayOfFormattedPersistentIdentifiers(): void {
		$response = $this->executeHandler(
			$this->newGetPersistentPageIdentifiersApi( format: 'foo/$1/bar' ),
			$this->createValidRequestData( [ self::PAGE_ID1, self::PAGE_ID2, self::PAGE_WITHOUT_ID2 ] )
		);

		$this->assertSame( 200, $response->getStatusCode() );
		$this->assertResponseHasIdentifiers(
			[
				self::PAGE_ID1 => '',
				self::PAGE_ID2 => "foo/id-42/bar"
			],
			$response
		);
	}

}
