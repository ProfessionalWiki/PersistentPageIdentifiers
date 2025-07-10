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

		$this->requireExtension( 'Persistent Page Identifiers' );
		$this->addDescription( 'Generates persistent identifiers for pages without them' );
	}

	public function execute(): void {
		$generatedIdsCount = 0;

		while ( true ) {
			$pageIds = $this->getNextBatchOfPageIdsForPagesWithoutPersistentIds();
			$batchSize = count( $pageIds );

			if ( $batchSize === 0 ) {
				break;
			}

			$this->output( "Generating persistent ids for batch of $batchSize pages\n" );

			$idMap = array_combine( $pageIds, $this->generateBulkPersistentIds( $batchSize ) );

			$this->output( 'Generated batch of persistent IDs: ' . json_encode( $idMap ) . "\n" );

			$this->savePersistentIds( $idMap );

			$generatedIdsCount += $batchSize;
		}

		$this->output( "Done. Generated $generatedIdsCount persistent IDs\n" );
	}

	/**
	 * @return int[]
	 */
	private function getNextBatchOfPageIdsForPagesWithoutPersistentIds(): array {
		return PersistentPageIdentifiersExtension::getInstance()->getPageIdsRepo()
			->getPageIdsOfPagesWithoutPersistentIds( limit: 1000 );
	}

	/**
	 * @param int $count
	 * @return string[]
	 */
	private function generateBulkPersistentIds( int $count ): array {
		return array_map(
			fn() => PersistentPageIdentifiersExtension::getInstance()->getIdGenerator()->generate(),
			range( 1, $count )
		);
	}

	private function savePersistentIds( array $idMap ): void {
		PersistentPageIdentifiersExtension::getInstance()->getPersistentPageIdentifiersRepo()->savePersistentIds( $idMap );
	}

}

$maintClass = GenerateMissingIdentifiers::class;
require_once RUN_MAINTENANCE_IF_MAIN;
