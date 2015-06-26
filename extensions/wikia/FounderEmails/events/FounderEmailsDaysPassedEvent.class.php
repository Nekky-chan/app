<?php

class FounderEmailsDaysPassedEvent extends FounderEmailsEvent {

	public function __construct( Array $data = array() ) {
		parent::__construct( 'daysPassed' );
		$this->setData( $data );
	}

	public function enabled ( User $user, $wgCityId = null ) {
		// This type of email cannot be disabled or avoided without unsubscribing from all email
		return true;
	}

	public function process( Array $events ) {
		global $wgExternalSharedDB, $wgTitle;

		$wgTitle = Title::newMainPage();
		foreach ( $events as $event ) {
			$wikiId = $event['wikiId'];
			$eventData = $event['data'];

			if ( $this->isInvalidWiki( $wikiId ) ) {
				continue;
			}

			if ( $this->isTooEarlyToSendEmail( $eventData['activateTime'] ) ) {
				continue;
			}

			$emailController = $this->getEmailController( $eventData['activateDays'] );
			if ( empty( $emailController ) ) {
				continue;
			}

			$adminIds = ( new WikiService )->getWikiAdminIds( $wikiId );
			foreach ( $adminIds as $adminId ) {

				$emailParams = [
					"targetUser" => User::newFromId( $adminId ),
					"wikiName" => $eventData['wikiName'],
					"wikiId" => $eventData['wikiId'],
				];


				F::app()->sendRequest( $emailController, 'handle', $emailParams );
			}

			$dbw = wfGetDB( DB_MASTER, [], $wgExternalSharedDB );
			$dbw->delete( 'founder_emails_event', array( 'feev_id' => $event['id'] ) );
		}

		// always return false to prevent deleting from FounderEmails::processEvent
		return false;
	}

	private function isInvalidWiki( $wikiId ) {
		return $wikiId == 0; // should "never" happen BugId:12717
	}

	private function isTooEarlyToSendEmail( $activateTime ) {
		return time() < $activateTime;
	}

	private function getEmailController( $activateDay ) {

		$emailController = "";
		if ( $activateDay == 0 ) {
			$emailController = 'Email\Controller\FounderTipsController';
		} elseif ( $activateDay == 3 ) {
			$emailController = 'Email\Controller\FounderTipsThreeDaysController';
		} elseif ( $activateDay == 10 ) {
			$emailController = 'Email\Controller\FounderTipsTenDaysController';
		}

		return $emailController;
	}

	public static function register( $wikiParams, $debugMode = false ) {
		global $wgFounderEmailsExtensionConfig, $wgCityId;

		$founderEmailObj = FounderEmails::getInstance();

		$wikiFounder = $founderEmailObj->getWikiFounder();
		$wikiFounder->setOption( "founderemails-joins-$wgCityId", true );
		$wikiFounder->setOption( "founderemails-edits-$wgCityId", true );

		$wikiFounder->saveSettings();

		foreach ( $wgFounderEmailsExtensionConfig['events']['daysPassed']['days'] as $activateDay ) {

			// Send the 0 day email, queue the rest
			$doProcess = $activateDay == 0 ? true : false;
			$eventData = array(
				'activateDays' => $activateDay,
				'activateTime' => time() + ( 86400 * $activateDay ),
				'wikiName' => $wikiParams['title'],
				'wikiId' => $wikiParams['city_id']
			);

			$founderEmailObj->registerEvent( new FounderEmailsDaysPassedEvent( $eventData ), $doProcess );
		}

		return true;
	}
}
