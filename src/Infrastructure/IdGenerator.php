<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Infrastructure;

interface IdGenerator {

	public function generate(): string;

}
