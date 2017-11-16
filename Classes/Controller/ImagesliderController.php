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
		$this->pageRenderer->addCssFile('http://www.jacklmoore.com/colorbox/example1/colorbox.css');
		$datauid = $data["uid"];
		$js = '
			var DYCON = DYCON || {};
			DYCON.events = [];
			window.DYCON = DYCON;
			//$(document).ready(function() {DYCON.ready();});
			DYCON.ready = function() {
				console.log("DYCON.ready called");
				jQuery(".dyconcarousel'.$data["uid"].'").colorbox({rel:"dyconcarousel'.$data["uid"].'"});	
			};
			DYCON.addScript = function( url, callback ) {
				var script = document.createElement( "script" );
				if( callback ) script.onload = callback;
				script.type = "text/javascript";
				script.src = url;
				document.body.appendChild( script );
				console.log("added:" + url); 
			};
			DYCON.addColorbox = function() {
				console.log("DYCON.addColorbox called"); 
				DYCON.addScript("https://cdnjs.cloudflare.com/ajax/libs/jquery.colorbox/1.6.4/jquery.colorbox.js", DYCON.ready);
			};
			if (typeof jQuery === "undefined") {
				console.log("jQuery is undefined -> loading"); 
				DYCON.addScript("https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js", DYCON.addColorbox);
			} else {
				DYCON.addColorbox();
			}
		';
		$this->pageRenderer->addJsFooterInlineCode('dyconcarousel', $js);
		$image = $data['image'];
		$this->view->assign('item', $data);
		// DebuggerUtility::var_dump($image);
	}

}
