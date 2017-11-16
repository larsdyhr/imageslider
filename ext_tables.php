<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	'dycon_carousel',
	'Configuration/TypoScript',
	'Dycon Image Slider');

if( intval(TYPO3_branch) > 6) {
	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
	$iconRegistry->registerIcon(
		'content-dycon-carousel',
		\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
		['source' => 'EXT:dycon_carousel/Resources/Public/Icons/ContentElements/carousel.svg']
	);
} else {

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'dycon_carousel',
		'Imageslider',
		'Dycon Image slider');

	\TYPO3\CMS\Backend\Sprite\SpriteManager::addSingleIcons(
		array(
			'content-dycon-carousel' => 'EXT:frontend/Resources/Public/Icons/ImageOrientation/above_center.gif',
		),
		"dycon_carousel"
	);
}

