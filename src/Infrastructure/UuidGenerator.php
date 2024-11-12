<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Infrastructure;

use Ramsey\Uuid\Uuid;

class UuidGenerator implements IdGenerator {

	public function generate(): string {
		return Uuid::uuid7()->toString();
	}

}
