<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use IContextSource;

class PersistentPageIdentifiersHooks {

	public static function onInfoAction( IContextSource $context, array &$pageInfo ): void {
		$pageInfo['header-basic'][] = [
			$context->msg( 'persistentpageidentifiers-info-label' ),
			'TODO'
		];
	}

}
