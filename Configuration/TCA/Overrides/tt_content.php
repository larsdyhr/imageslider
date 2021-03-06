<?php
	if( intval(TYPO3_branch) > 6) {
		$LLLocation = 'LLL:EXT:frontend/Resources/Private/Language';
	} else {
		$LLLocation = 'LLL:EXT:cms';
	}

/***************
 * Add Content Elements to List
$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] = array(
    array(
        'LLL:EXT:dycon_carousel/Resources/Private/Language/Backend.xlf:content_element.carouseltitle'. " test",
        'dycon_carousel',
		( intval(TYPO3_branch) > 6) ? 'EXT:frontend/Resources/Public/Icons/ImageOrientation/above_center.gif' : '../typo3/gfx/selicons/above_center.gif'
    ),
);
 */
	$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = array(
		'LLL:EXT:dycon_carousel/Resources/Private/Language/locallang.xlf:content_element.carouseltitle',
		'dyconcarousel_imageslider',
		( intval(TYPO3_branch) > 6) ? 'EXT:frontend/Resources/Public/Icons/ImageOrientation/above_center.gif' : 'gfx/i/tt_content_image.gif'
	);
	$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = array(
		'LLL:EXT:dycon_carousel/Resources/Private/Language/locallang.xlf:content_element.videotitle',
		'dyconcarousel_videostream',
		( intval(TYPO3_branch) > 6) ? 'EXT:frontend/Resources/Public/Icons/ImageOrientation/above_center.gif' : 'gfx/i/tt_content_multimedia.gif'
	);

/***************
 * Add FlexForms for content element configuration
 * This does not work properly (at least not until TYPO3 8):
 * Flexform does not handle FAL fields correct (until TYPO3 vers. 8).
 * So using the existing fields. : https://forge.typo3.org/issues/68045
 * And thus not using flexform for inline FAL field
 **/

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:dycon_carousel/Configuration/FlexForms/Carousel.xml',
    'dyconcarousel_imageslider'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	'*',
	'FILE:EXT:dycon_carousel/Configuration/FlexForms/Video.xml',
	'dyconcarousel_videostream'
);

// @note: If I want to override the default rendering of the uploaded files (and add a field or whatever),
// I probably need a new field to be added to tt_content,
// as I will have to override the general image field TCA to override the inline record
// (which is a sys_file_ref pointing to sys_file).
//--palette--;'.$LLLocation.'/locallang_ttc.xlf:palette.multimediafiles;multimediafiles,
//--linebreak--,pi_flexform,
	//$GLOBALS['TCA']['tt_content']['palettes']['dyconcarousel_imageslider'] = array(
	//	'showitem' => ''
	//);
	$GLOBALS['TCA']['tt_content']['types']['dyconcarousel_imageslider'] = array(
		'showitem' => '
			CType;LLL:EXT:cms/locallang_ttc.xlf:CType_formlabel,
			colPos;LLL:EXT:cms/locallang_ttc.xlf:colPos_formlabel, 
			sys_language_uid;'.$LLLocation.'/locallang_ttc.xlf:sys_language_uid_formlabel, 
			l18n_parent,
			image;LLL:EXT:dycon_carousel/Resources/Private/Language/locallang.xlf:content_element.image,
			pi_flexform;LLL:EXT:dycon_carousel/Resources/Private/Language/locallang.xlf:content_element.sliderflexformtitle,
			--div--;'.$LLLocation.'/locallang_ttc.xlf:tabs.access,
				--palette--;'.$LLLocation.'/locallang_ttc.xlf:palette.visibility;visibility, 
				--palette--;'.$LLLocation.'/locallang_ttc.xlf:palette.access;access'
	);
	$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['dyconcarousel_imageslider'] = 'mimetypes-x-content-image';
	$GLOBALS['TCA']['tt_content']['columns']['image']['config']['filter'][] = array (
		'userFunc' => 'DYCON\DyconCarousel\Imagefilter->doFilter',
		'parameters' => array(
			'minWidth' => '1920',
			'minHeight' => '1160'
		)
	);
	$GLOBALS['TCA']['tt_content']['types']['dyconcarousel_videostream'] = array(
		'showitem' => '
			CType;LLL:EXT:cms/locallang_ttc.xlf:CType_formlabel,
			colPos;LLL:EXT:cms/locallang_ttc.xlf:colPos_formlabel, 
			sys_language_uid;'.$LLLocation.'/locallang_ttc.xlf:sys_language_uid_formlabel, 
			l18n_parent,
			pi_flexform;LLL:EXT:dycon_carousel/Resources/Private/Language/locallang.xlf:content_element.videoplugintitle,
			--div--;'.$LLLocation.'/locallang_ttc.xlf:tabs.access,
				--palette--;'.$LLLocation.'/locallang_ttc.xlf:palette.visibility;visibility, 
				--palette--;'.$LLLocation.'/locallang_ttc.xlf:palette.access;access'
	);
	$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['dyconcarousel_videostream'] = 'mimetypes-x-content-multimedia';

