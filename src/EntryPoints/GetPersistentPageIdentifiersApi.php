<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Presentation\PersistentPageIdFormatter;
use Wikimedia\ParamValidator\ParamValidator;

class GetPersistentPageIdentifiersApi extends SimpleHandler {

	private const PARAM_PAGE_IDS = 'ids';

	public function __construct(
		private readonly PersistentPageIdentifiersRepo $repo,
		private readonly PersistentPageIdFormatter $formatter
	) {
	}

	public function run(): Response {
		$params = $this->getValidatedParams();

		return $this->createResponse(
			$this->repo->getPersistentIds( $params[self::PARAM_PAGE_IDS] )
		);
	}

	/**
	 * @param array<int, string|null> $ids
	 */
	private function createResponse( array $ids ): Response {
		return $this->getResponseFactory()->createJson( [
			'identifiers' => array_map(
				fn( ?string $id ) => $this->formatter->format( $id ),
				$ids
			)
		] );
	}

	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function getParamSettings(): array {
		return [
			self::PARAM_PAGE_IDS => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_ISMULTI => true,
			]
		];
	}

	public function needsWriteAccess(): bool {
		return false;
	}

}
