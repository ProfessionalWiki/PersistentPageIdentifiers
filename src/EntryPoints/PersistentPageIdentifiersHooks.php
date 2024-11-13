<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use DatabaseUpdater;
use IContextSource;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\User\UserIdentity;
use Parser;
use ProfessionalWiki\PersistentPageIdentifiers\PersistentPageIdentifiersExtension;
use WikiPage;

class PersistentPageIdentifiersHooks {

	public static function onInfoAction( IContextSource $context, array &$pageInfo ): void {
		$pageInfo['header-basic'][] = [
			$context->msg( 'persistentpageidentifiers-info-label' ),
			PersistentPageIdentifiersExtension::getInstance()->getPersistentPageIdentifiersRepo()->getPersistentId(
				$context->getWikiPage()->getId()
			)
		];
	}

	public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ): void {
		$updater->addExtensionTable(
			'persistent_page_ids',
			__DIR__ . '/../../sql/persistent_page_ids.sql'
		);
	}

	public static function onParserFirstCallInit( Parser $parser ): void {
		$parser->setFunctionHook(
			'ppid',
			PersistentPageIdentifiersExtension::getInstance()->newPersistentPageIdFunction()->handleParserFunctionCall( ... )
		);
	}

	public static function onPageSaveComplete(
		WikiPage $wikiPage,
		UserIdentity $user,
		string $summary,
		int $flags,
		RevisionRecord $revisionRecord,
		EditResult $editResult
	): void {
		if ( !$editResult->isNew() ) {
			return;
		}

		PersistentPageIdentifiersExtension::getInstance()->newCreatePersistentPageIdentifier()->createId( $wikiPage->getId() );
	}

	public static function onRevisionUndeleted( RevisionRecord $restoredRevision, ?int $oldPageId ): void {
		if ( $oldPageId !== null ) {
			return;
		}

		// TODO: untested
		$repo = PersistentPageIdentifiersExtension::getInstance()->getPersistentPageIdentifiersRepo();
		$oldPersistentId = $repo->getPersistentId( $restoredRevision->getPageId() );

		if ( $oldPersistentId === null ) {
			return;
		}

		$repo->savePersistentId( $restoredRevision->getPageId(), $oldPersistentId );
	}

}
