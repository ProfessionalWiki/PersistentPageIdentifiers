<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use CommentStoreComment;
use IContextSource;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\User\UserIdentity;
use ProfessionalWiki\PersistentPageIdentifiers\PersistentPageIdentifiersExtension;
use Status;

class PersistentPageIdentifiersHooks {

	public static function onMultiContentSave(
		RenderedRevision $renderedRevision,
		UserIdentity $user,
		CommentStoreComment $summary,
		int $flags,
		Status $hookStatus
	): void {
		PersistentPageIdentifiersExtension::getInstance()->getPersistentPageIdSaver()->savePageId(
			$renderedRevision->getRevisionParserOutput(),
			self::getOrGeneratePersistentPageId( $renderedRevision->getRevision()->getPage() )
		);
	}

	private static function getOrGeneratePersistentPageId( PageIdentity $page ): string {
		return self::getPersistentPageId( $page )
			?? PersistentPageIdentifiersExtension::getInstance()->getIdGenerator()->generate();
	}

	private static function getPersistentPageId( PageIdentity $page ): ?string {
		return MediaWikiServices::getInstance()->getPageProps()->getProperties(
			$page,
			PersistentPageIdentifiersExtension::PERSISTENT_PAGE_ID_PROPERTY
		)[$page->getId()] ?? null;
	}

	public static function onInfoAction( IContextSource $context, array &$pageInfo ): void {
		$pageInfo['header-basic'][] = [
			$context->msg( 'persistentpageidentifiers-info-label' ),
			$context->getWikiPage()->getParserOutput()->getPageProperty(
				PersistentPageIdentifiersExtension::PERSISTENT_PAGE_ID_PROPERTY
			)
		];
	}

}
