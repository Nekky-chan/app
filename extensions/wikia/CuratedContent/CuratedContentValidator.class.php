<?php

class CuratedContentValidator {
	const LABEL_MAX_LENGTH = 48;

	const ERR_DUPLICATED_LABEL = 'duplicatedLabel';
	const ERR_IMAGE_MISSING = 'imageMissing';
	const ERR_TOO_LONG_LABEL = 'tooLongLabel';
	const ERR_ITEMS_MISSING = 'itemsMissing';
	const ERR_NOT_SUPPORTED_TYPE = 'notSupportedType';
	const ERR_VIDEO_WITHOUT_INFO = 'videoNotHaveInfo';
	const ERR_VIDEO_NOT_SUPPORTED = 'videoNotSupportProvider';
	const ERR_ARTICLE_NOT_FOUND = 'articleNotFound';
	const ERR_EMPTY_LABEL = 'emptyLabel';
	const ERR_NO_CATEGORY_IN_TAG = 'noCategoryInTag';

	private $errors;
	private $existingLabels;
	private $hasOptionalSection;

	public function __construct() {
		$this->reset();
	}

	public function reset() {
		$this->errors = [ ];
		$this->existingLabels = [ ];
		$this->hasOptionalSection = false;
	}

	public function validateData( $data ) {
		$this->reset();

		// validate sections
		foreach ( $data as $section ) {
			if ( !empty( $section['featured'] ) ) {
				$this->validateItems( $section );
			} else {
				$this->validateSection( $section );
				if ( !empty( $section['title'] ) ) {
					$this->validateItemsExist( $section );
					$this->validateItems( $section );
					$this->validateItemsTypes( $section );
				}
			}
		}
		// also check for duplicate labels
		$this->validateDuplicatedLabels();

		return $this->errors;
	}

	public function validateDuplicatedLabels() {
		foreach ( array_count_values( $this->existingLabels ) as $label => $count ) {
			if ( $count > 1 ) {
				// we found label that has >1 occurences, let's iterate though them
				while ( ( $title = array_search( $label, $this->existingLabels ) ) !== null)
				{
					$this->error( [ 'title' => $title ], self::ERR_DUPLICATED_LABEL );
					unset( $this->existingLabels[$title] );
				}
			}
		}
	}

	public function getErrors() {
		return $this->errors;
	}

	private function error( $itemWithTitle, $errorString ) {
		if ( array_key_exists( 'title', $itemWithTitle ) && !empty( $errorString ) ) {
			$this->errors[] = [ 'title' => $itemWithTitle['title'], 'reason' => $errorString ];
		}
	}

	public function validateFeaturedSectionItems( $section ) {
		if ( !empty( $section['items'] ) && is_array( $section['items'] ) ) {
			foreach ($section['items'] as $item) {
				$this->validateItem( $item );
			}
		}
	}

	private function validateImage( $sectionOrItem ) {
		if ( empty( $sectionOrItem['image_id'] ) ) {
			$this->error( $sectionOrItem, self::ERR_IMAGE_MISSING );
		}
	}

	public function validateItemsExist( $section ) {
		if ( empty( $section['items'] ) || !is_array( $section['items'] ) ) {
			$this->error( $section, self::ERR_ITEMS_MISSING );
		}
	}

	public function validateItems( $section ) {
		if (!empty($section['items']) && is_array($section['items'])) {
			foreach ($section['items'] as $item) {
				$this->validateItem( $item );
			}
		}
	}

	public function validateSection( $section ) {
		// check for "optional" section - it has empty label, but there can be only ONE
		if ( empty( $section['title'] ) ) {
			if ( $this->hasOptionalSection ) {
				$this->error( $section, self::ERR_DUPLICATED_LABEL );
			} else {
				$this->hasOptionalSection = true;
			}
		}

		if ( strlen( $section['title'] ) > self::LABEL_MAX_LENGTH ) {
			$this->error( $section, self::ERR_TOO_LONG_LABEL );
		}

		if ( empty( $section['featured'] ) && !empty( $section['title'] ) ) {
			$this->validateImage( $section );
		}
		if ( !empty( $section['title'] ) ) {
			$this->existingLabels[$section['title']] = $section['title'];
		}
	}

	public function validateItemType( $item ) {
		if ( $item['type'] !== CuratedContentHelper::STR_CATEGORY ) {
			$this->error( $item, self::ERR_NO_CATEGORY_IN_TAG );
		}
	}

	public function validateItemsTypes( $section ) {
		if ( !empty( $section['items'] ) && is_array( $section['items'] ) ) {
			foreach ( $section['items'] as $item ) {
				$this->validateItemType( $item );
			}
		}
	}

	public function validateItem( $item ) {
		$this->validateImage( $item );

		if ( empty( $item['label'] ) ) {
			$this->error( $item, self::ERR_EMPTY_LABEL );
		}

		if ( strlen( $item['label'] ) > self::LABEL_MAX_LENGTH ) {
			$this->error( $item, self::ERR_TOO_LONG_LABEL );
		}

		if ( empty( $item['type'] ) ) {
			$this->error( $item, self::ERR_NOT_SUPPORTED_TYPE );
		}

		if ( $item['type'] === CuratedContentHelper::STR_VIDEO ) {
			if ( empty( $item['video_info'] ) ) {
				$this->error( $item, self::ERR_VIDEO_WITHOUT_INFO );
			} elseif ( !self::isSupportedProvider( $item['video_info']['provider'] ) ) {
				$this->error( $item, self::ERR_VIDEO_NOT_SUPPORTED );
			}
		}

		if ( self::needsArticleId( $item['type'] ) && empty( $item['article_id'] ) ) {
			$this->error( $item, self::ERR_ARTICLE_NOT_FOUND );
		}

		$this->existingLabels[$item['title']] = $item['label'];
	}

	private static function needsArticleId( $type ) {
		return $type !== CuratedContentHelper::STR_CATEGORY;
	}

	private static function isSupportedProvider( $provider ) {
		return ( $provider === 'youtube' ) || ( startsWith( $provider, 'ooyala' ) );
	}
}
