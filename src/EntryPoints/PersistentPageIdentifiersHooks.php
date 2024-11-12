<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use DatabaseUpdater;
use IContextSource;

class PersistentPageIdentifiersHooks {

	public static function onInfoAction( IContextSource $context, array &$pageInfo ): void {
		$pageInfo['header-basic'][] = [
			$context->msg( 'persistentpageidentifiers-info-label' ),
			'TODO'
		];
	}

	public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ): void {
		$updater->addExtensionTable(
			'persistent_page_ids',
			__DIR__ . '/../../sql/persistent_page_ids.sql'
		);
	}

}
