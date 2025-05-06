<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use FormSpecialPage;
use ProfessionalWiki\PersistentPageIdentifiers\PersistentPageIdentifiersExtension;
use Status;
use Title;

class SpecialPersistentPageIdentifierResolver extends FormSpecialPage {

	public function __construct() {
		parent::__construct( 'PersistentPageIdentifierResolver' );
	}

	public function execute( $subPage ): void {
		parent::execute( $subPage );

		if ( $subPage === null || $subPage === '' ) {
			return;
		}

		$title = $this->getTitleFromPersistentId( $subPage );

		if ( $title === null ) {
			return;
		}

		$this->getOutput()->redirect( $title->getFullURL() );
	}

	protected function getFormFields(): array {
		return [
			'persistentpageidentifier' => [
				'type' => 'text',
				'label-message' => 'persistentpageidentifiers-info-label',
				'required' => true,
			]
		];
	}

	public function onSubmit( array $data ): Status|bool {
		$title = $this->getTitleFromPersistentId( $data['persistentpageidentifier'] );

		if ( $title === null ) {
			// Message: persistentpageidentifierresolver-not-exists
			return Status::newFatal( $this->getMessagePrefix() . '-not-exists' );
		}

		$this->getOutput()->redirect( $title->getFullURL() );

		return true;
	}

	protected function getDisplayFormat(): string {
		return 'ooui';
	}

	public function getGroupName(): string {
		return 'redirects';
	}

	private function getTitleFromPersistentId( string $persistentId ): ?Title {
		$pageId = $this->getPageIdFromPersistentId( $persistentId );

		if ( $pageId !== null ) {
			return Title::newFromID( $pageId );
		}

		return null;
	}

	private function getPageIdFromPersistentId( string $persistentId ): ?int {
		return PersistentPageIdentifiersExtension::getInstance()->getPersistentPageIdentifiersRepo()->getPageIdFromPersistentId( $persistentId );
	}

}
