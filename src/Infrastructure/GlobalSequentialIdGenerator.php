<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Infrastructure;

class GlobalSequentialIdGenerator implements IdGenerator {

	private static int $counter = 0;

	public function generate(): string {
		return 'id-' . ++self::$counter;
	}

}
