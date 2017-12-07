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
		if(!isset($tceMain->datamap["tt_content"]) OR !is_array($tceMain->datamap["tt_content"])) {
			return $parameters['values'];
		}
		$tt_contentRec = array();
		$tt_contentRec_Array = $tceMain->datamap["tt_content"];
		foreach($tt_contentRec_Array as $key => $val) {
			$tt_contentRec = $val;
			$tt_contentRec["uid"] = $key;
		}
		if(!isset($tt_contentRec["CType"])) {
			return $parameters['values'];
		}
		if($tt_contentRec["CType"] ==! "dyconcarousel_imageslider") {
			return $parameters['values'];
		}
		$values = $parameters['values'];
		$minWidth = 1920;
		$minHeight = 1160;
		if ($parameters['minWidth']) {
			$minWidth = (int)$parameters['minWidth'];
		}
		if ($parameters['minHeight']) {
			$minHeight = (int)$parameters['minHeight'];
		}
		/*
		$interval = 4000;
		$wrap = 1;
		$slideshow = 1;
		$colourstyle = 1;
		if(isset($tt_contentRec['pi_flexform'])) {
			$interval = $this->recursive_array_search('interval',$tt_contentRec['pi_flexform'] );
			$wrap = $this->recursive_array_search('wrap',$tt_contentRec['pi_flexform'] );
			$slideshow = $this->recursive_array_search('slideshow',$tt_contentRec['pi_flexform'] );
			$colourstyle = $this->recursive_array_search('colourstyle',$tt_contentRec['pi_flexform'] );
		}
		*/
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
				if (isset($fileProps["width"]) && $fileProps["width"] >= $minWidth && $fileProps["height"] >= $minHeight) {
					$cleanValues[] = $value;
				} else {
					// Remove the erroneously created reference record again
					$tceMain->deleteAction('sys_file_reference', $fileReferenceUid);
					$msg = "Filen: ".$fileProps["name"]. " var ikke stor nok. Den skal mindst være ".$minWidth."px bred og ".$minHeight."px høj. Filen er ".$fileProps["width"]."px bred og ".$fileProps["height"]."px høj";
					/** @var FlashMessage $flashMessage */
					$flashMessage = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $msg, '', FlashMessage::INFO, true);
					/** @var $flashMessageService FlashMessageService */
					$flashMessageService = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessageService');
					$defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
					$defaultFlashMessageQueue->enqueue($flashMessage);
				}
			}
		}
		return $cleanValues;
	}
	public function recursive_array_search($needle,$haystack) {
		foreach($haystack as $key=>$value) {
			if($key==$needle) {
				return is_numeric($value) ? (int)$value : (is_array($value) ? array_pop($value) : (string)$value);
			} elseif (is_array($value)) {
				$next = $this->recursive_array_search($needle,$value);
				if ($next) {
					return $next;
				}
			}
		}
		return false;
	}
}
