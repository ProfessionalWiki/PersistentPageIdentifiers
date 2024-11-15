<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Maintenance;

use Maintenance;
use ProfessionalWiki\PersistentPageIdentifiers\PersistentPageIdentifiersExtension;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../../..';

require_once $basePath . '/maintenance/Maintenance.php';

class GenerateMissingIdentifiers extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->requireExtension( 'PersistentPageIdentifiers' );
		$this->addDescription( 'Generates persistent identifiers for pages without them' );
	}

	public function execute(): void {
		$generatedIdsCount = 0;

		while ( true ) {
			$pageIds = $this->getNextBatchOfPageIdsForPagesWithoutPersistentIds();
			$batchCount = count( $pageIds );

			if ( $batchCount === 0 ) {
				break;
			}

			$this->output( "Generating persistent ids for batch of $batchCount pages\n" );

			$this->savePersistentIds( $pageIds, $this->generatePersistentIds( $batchCount ) );

			$generatedIdsCount += $batchCount;
		}

		$this->output( "Generated $generatedIdsCount persistent IDs\n" );
	}

	/**
	 * @return int[]
	 */
	private function getNextBatchOfPageIdsForPagesWithoutPersistentIds(): array {
		return PersistentPageIdentifiersExtension::getInstance()->getPageIdsRepo()
			->getPageIdsOfPagesWithoutPersistentIds( limit: 100 );
	}

	/**
	 * @param int $count
	 * @return string[]
	 */
	private function generatePersistentIds( int $count ): array {
		return array_map(
			fn() => PersistentPageIdentifiersExtension::getInstance()->getIdGenerator()->generate(),
			range( 1, $count )
		);
	}

	private function savePersistentIds( array $pageIds, array $persistentIds ): void {
		PersistentPageIdentifiersExtension::getInstance()->getPersistentPageIdentifiersRepo()->savePersistentIds(
			array_combine( $pageIds, $persistentIds )
		);
	}

}

$maintClass = GenerateMissingIdentifiers::class;
require_once RUN_MAINTENANCE_IF_MAIN;
