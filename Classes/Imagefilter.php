<?php
namespace DYCON\DyconCarousel;

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\File\BasicFileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Imagefilter {

	/**
	 * Entry method for use as TCEMain "inline" field filter
	 *
	 * @param array $parameters
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $tceMain
	 * @return array
	 */
	public function doFilter(array $parameters, \TYPO3\CMS\Core\DataHandling\DataHandler $tceMain)
	{
		$values = $parameters['values'];
		$minWidth = 500;
		$minHeight = 500;
		if ($parameters['minWidth']) {
			$minWidth = (int)$parameters['minWidth'];
		}
		if ($parameters['minHeight']) {
			$minHeight = (int)$parameters['minHeight'];
		}
		$cleanValues = [];
		if (is_array($values)) {
			foreach ($values as $value) {
				if (empty($value)) {
					continue;
				}
				$parts = \TYPO3\CMS\Core\Utility\GeneralUtility::revExplode('_', $value, 2);
				$fileReferenceUid = $parts[count($parts) - 1];
				$fileReference = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->getFileReferenceObject($fileReferenceUid);
				$fileProps = $fileReference->getProperties();
				// debug($fileProps);
				if (isset($fileProps["width"]) && $fileProps["width"] >= $minWidth) {
					$cleanValues[] = $value;
				} else {
					// Remove the erroneously created reference record again
					$tceMain->deleteAction('sys_file_reference', $fileReferenceUid);
					$msg = "Filen: ".$fileProps["name"]. " var ikke stor nok. Den skal mindst være ".$minWidth."px bred og ".$minHeight."px høj. Filen er ".$fileProps["width"]."px bred og ".$fileProps["height"]."px høj";
					/** @var FlashMessage $flashMessage */
					$flashMessage = GeneralUtility::makeInstance(FlashMessage::class, $msg, '', FlashMessage::INFO, true);
					/** @var $flashMessageService FlashMessageService */
					$flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
					$defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
					$defaultFlashMessageQueue->enqueue($flashMessage);
				}
			}
		}
		return $cleanValues;
	}
}