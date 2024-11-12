<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Persistence;

use ParserOutput;
use ProfessionalWiki\PersistentPageIdentifiers\PersistentPageIdentifiersExtension;

class PersistentPageIdSaver {

	public function savePageId( ParserOutput $parser, string $id ): void {
		$parser->setPageProperty(
			PersistentPageIdentifiersExtension::PERSISTENT_PAGE_ID_PROPERTY,
			$id
		);
	}

}
