<?php
namespace DYCON\DyconCarousel\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;


/**
 *
 *
 * @package dycon_carousel
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ImagesliderController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	/**
	 * @var \TYPO3\CMS\Core\Page\PageRenderer
	 * @inject
	 */
	protected $pageRenderer;

	/**
	 * show action
	 *
	 * @return void
	 */
	public function showAction() {
		$data = $this->configurationManager->getContentObject()->data;
		// $this->configurationManager->getContentObject()->readFlexformIntoConf($data['pi_flexform'], $data);
		DebuggerUtility::var_dump($this->settings);
		$interval = isset($this->settings["interval"]) ? (int)$this->settings["interval"] : 5;
		$wrap = isset($this->settings["wrap"]) ? (int)$this->settings["wrap"] : 1;
		$slideshow = isset($this->settings["slideshow"]) ? (int)$this->settings["slideshow"] : 0;
		$colourstyle = isset($this->settings["colourstyle"]) ? (int)$this->settings["colourstyle"] : 0;

		$interval = $interval * 1000;
		$this->view->assign('interval', $interval);
		$this->view->assign('colourstyle', $colourstyle);
		$this->view->assign('slideshow', $slideshow);
		$this->view->assign('wrap', $wrap);

		$this->pageRenderer->addCssFile('http://www.jacklmoore.com/colorbox/example1/colorbox.css');
		$datauid = $data["uid"];
		$js = '
			var DYCON = DYCON || {};
			DYCON.slides = [];
			window.DYCON = DYCON;
			DYCON.ready = function() {
				jQuery(".dyconcarousel'.$data["uid"].'").colorbox({rel:"dyconcarousel'.$data["uid"].'",transition:"fade",slideshow:'.($slideshow ? "true":"false").',slideshowSpeed: '.$interval.',slideshowAuto:true,loop:'.($wrap ? "true":"false").',iframe:false});
			};
			DYCON.addScript = function( url, callback ) {
				var script = document.createElement( "script" );
				if( callback ) script.onload = callback;
				script.type = "text/javascript";
				script.src = url;
				document.body.appendChild( script );
			};
			DYCON.addColorbox = function() {
				DYCON.addScript("https://cdnjs.cloudflare.com/ajax/libs/jquery.colorbox/1.6.4/jquery.colorbox.js", DYCON.ready);
			};
			if (typeof jQuery === "undefined") {
				DYCON.addScript("https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js", DYCON.addColorbox);
			} else {
				DYCON.addColorbox();
			}
		';
		$this->pageRenderer->addJsFooterInlineCode('dyconcarousel', $js);
		$this->view->assign('item', $data);
		// DebuggerUtility::var_dump($data);
	}
}
