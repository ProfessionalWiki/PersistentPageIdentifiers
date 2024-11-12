<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Maintenance;

use Maintenance;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../../..';

require_once $basePath . '/maintenance/Maintenance.php';

class GenerateMissingIdentifiers extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->requireExtension( 'PersistentPageIdentifiers' );
		$this->addDescription( 'Generates persistent identifiers for pages without them' );
	}

	public function execute(): void {
		$this->output( 'TODO' );
	}

}

$maintClass = GenerateMissingIdentifiers::class;
require_once RUN_MAINTENANCE_IF_MAIN;
