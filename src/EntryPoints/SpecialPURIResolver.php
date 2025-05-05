<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use FormSpecialPage;
use ProfessionalWiki\PersistentPageIdentifiers\PersistentPageIdentifiersExtension;
use Status;
use Title;

class SpecialPURIResolver extends FormSpecialPage {

	public function __construct() {
		parent::__construct( 'PURIResolver' );
	}

	public function execute( $subPage ): void {
		parent::execute( $subPage );

		if ( !$subPage ) {
			return;
		}

		$title = $this->getTitleFromPersistentId( $subPage );

		if ( !$title ) {
			return;
		}

		$this->getOutput()->redirect( $title->getFullURL() );
	}

	protected function getFormFields() {
		return [
			'puri' => [
				'type' => 'text',
				'label-message' => 'puriresolver-puri',
				'required' => true,
			]
		];
	}

	public function onSubmit( array $data ) {
		$title = $this->getTitleFromPersistentId( $data['puri'] );

		if ( !$title ) {
			// Message: puri-not-exists
			return Status::newFatal( $this->getMessagePrefix() . '-not-exists' );
		}

		$this->getOutput()->redirect( $title->getFullURL() );
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	public function getGroupName(): string {
		return 'redirects';
	}

	private function getTitleFromPersistentId( string $persistentId ): ?Title {
		$pageId = $this->getPageIdFromPersistentId( $persistentId );

		if ( $pageId ) {
			return Title::newFromID( $pageId );
		}

		return null;
	}

	private function getPageIdFromPersistentId( string $persistentId ): ?int {
		return PersistentPageIdentifiersExtension::getInstance()->getPageIdsRepo()->getPageIdFromPersistentId( $persistentId );
	}

}
