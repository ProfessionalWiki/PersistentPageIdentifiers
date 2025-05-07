<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use FormSpecialPage;
use ProfessionalWiki\PersistentPageIdentifiers\PersistentPageIdentifiersExtension;
use ProfessionalWiki\PersistentPageIdentifiers\Presentation\PersistentPageIdFormatter;
use Status;
use Title;

class SpecialPersistentPageIdentifierResolver extends FormSpecialPage {

	public function __construct() {
		parent::__construct( 'PersistentPageIdentifierResolver' );
	}

	public function execute( $subPage ): void {
		// Redirect to the page immediately if it is valid
		$url = $this->getUrlFromPersistentId( $subPage );
		if ( $url !== null ) {
			$this->getOutput()->redirect( $url );
			return;
		}

		parent::execute( $subPage );
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
		$url = $this->getUrlFromPersistentId( $data['persistentpageidentifier'] );
		if ( $url === null ) {
			return Status::newFatal( $this->getMessagePrefix() . '-not-exists' );
		}

		$this->getOutput()->redirect( $url );
		return true;
	}

	protected function getDisplayFormat(): string {
		return 'ooui';
	}

	public function getGroupName(): string {
		return 'redirects';
	}

	private function getUrlFromPersistentId( ?string $persistentId ): ?string {
		if ( $persistentId === null || $persistentId === '' ) {
			return null;
		}

		$title = $this->getTitleFromPersistentId( $this->extractId( $persistentId ) );

		if ( $title === null || !$title->exists() ) {
			return null;
		}

		return $title->getFullURL();
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

	private function extractId( string $input ): string {
		return PersistentPageIdentifiersExtension::getInstance()->newPersistentPageIdFormatter()->extractId( $input );
	}

}
