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
		$pageIds = PersistentPageIdentifiersExtension::getInstance()->getPageIdsRepo()
			->getPageIdsOfPagesWithoutPersistentIds();

		foreach ( $pageIds as $pageId ) {
			$this->savePersistentId( intval( $pageId ), $this->generatePersistentId() );
		}

		$pageIdsCount = count( $pageIds );

		$this->output( "Created $pageIdsCount persistent IDs\n" );
	}

	private function generatePersistentId(): string {
		return PersistentPageIdentifiersExtension::getInstance()->getIdGenerator()->generate();
	}

	private function savePersistentId( int $pageId, string $persistentId ): void {
		PersistentPageIdentifiersExtension::getInstance()->getPersistentPageIdentifiersRepo()->savePersistentId(
			$pageId,
			$persistentId
		);
	}

}

$maintClass = GenerateMissingIdentifiers::class;
require_once RUN_MAINTENANCE_IF_MAIN;
