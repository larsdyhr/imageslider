<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// 	version_compare("4.3", "4.2.0", ">=") ...
if( intval(TYPO3_branch) ==6) {
//
}
/**
 * From TYPO3 7 - to add a custom icon we need to register it first.
 * To be implemented later:
 *	Use the new `IconFactory` class instead of `IconUtility`.
 * For content element wizard register your icon in `IconRegistry::registerIcon()` and use the new setting:
 * `mod.wizards.newContentElement.wizardItems.*.elements.*.iconIdentifier`
 * // Add Content Elements to newContentElement Wizard
 *
 **/

// Not using iconIdentifier yet
	// iconIdentifier = content-dycon-carousel
// content-carousel-image.svg
	// icon = EXT:frontend/Resources/Public/Icons/ImageOrientation/above_center.gif
// iconIdentifier = content-carousel-image
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'DYCON.dycon_carousel',
	'Imageslider',
	array('Imageslider' => 'show'),
	array('Imageslider' => ''),
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
//		iconIdentifier = content-dycon-carousel
// icon = EXT:dycon_carousel/Resources/Public/Images/images_only.gif
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
'mod.wizards.newContentElement.wizardItems.common.elements.dyconcarousel_imageslider {
        icon = gfx/i/tt_content_image.gif
        title = LLL:EXT:dycon_carousel/Resources/Private/Language/Backend.xlf:content_element.carouseltitle
        description = LLL:EXT:dycon_carousel/Resources/Private/Language/Backend.xlf:content_element.carouseldescription
        tt_content_defValues {
            CType = dyconcarousel_imageslider
			imagewidth = 1000
			imageheight = 900
			table_border = 5
        }
    }
    mod.wizards.newContentElement.wizardItems.common.show := addToList(dyconcarousel_imageslider)'
);

