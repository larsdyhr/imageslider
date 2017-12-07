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
	 * @var array Provider that can be handled, the provider is equal the hostname and needs a process function
	 */
	protected $mediaProvider = array(
		'youtube',
		'youtu',
		'vimeo'
	);

	/**
	 * The converted URL to youtube or vimeo - adjusted to be embedded
	 */
	protected $embedUrl = "";


	/**
	 * show action
	 * This is the main action for rendering a plugin of type Dycon Image Slider
	 *
	 * @return void
	 */
	public function showAction() {
		$data = $this->configurationManager->getContentObject()->data;
		// $this->configurationManager->getContentObject()->readFlexformIntoConf($data['pi_flexform'], $data);
		$interval = isset($this->settings["interval"]) ? (int)$this->settings["interval"] : 5;
		$wrap = isset($this->settings["wrap"]) ? (int)$this->settings["wrap"] : 1;
		$slideshow = isset($this->settings["slideshow"]) ? (int)$this->settings["slideshow"] : 0;
		$colourstyle = isset($this->settings["colourstyle"]) ? (int)$this->settings["colourstyle"] : 0;

		$interval = $interval * 1000;
		$this->view->assign('interval', $interval);
		$this->view->assign('colourstyle', $colourstyle);
		$this->view->assign('slideshow', $slideshow);
		$this->view->assign('wrap', $wrap);
		$data["width"] = 1920;
		$data["height"] = 1160;
		$this->pageRenderer->addCssFile('http://www.jacklmoore.com/colorbox/example1/colorbox.css');
		$datauid = $data["uid"];
		$js = '
			var DYCON = DYCON || {};
			DYCON.slides = [];
			window.DYCON = DYCON;
			DYCON.ready = function() {
				jQuery(".dyconcarousel'.$data["uid"].'").colorbox({rel:"dyconcarousel'.$data["uid"].'",transition:"fade",slideshow:'.($slideshow ? "true":"false").',slideshowSpeed: '.$interval.',slideshowAuto:true,loop:'.($wrap ? "true":"false").',iframe:false,maxWidth:1920,width:1700});
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
	/**
	 * videoaction
	 * This is the main action for rendering a plugin of type Dycon Viedoestream
	 *
	 * @return void
	 */
	public function videoAction() {
		$data = $this->configurationManager->getContentObject()->data;
		$link = isset($this->settings["link"]) ? $this->settings["link"] : "";
		$viewsenabled = isset($this->settings["viewsenabled"]) ? (int)$this->settings["viewsenabled"] : 1;
		// Removed again $shareenabled = isset($this->settings["shareenabled"]) ? (int)$this->settings["shareenabled"] : 1;
		$headline = isset($this->settings["headline"]) ? $this->settings["headline"] : "";
		$body = isset($this->settings["body"]) ? $this->settings["body"] : "";

		$this->view->assign('item', $data);
		$this->view->assign('link', $link);
		$this->view->assign('viewsenabled', $viewsenabled);
		// Removed $this->view->assign('shareenabled', $shareenabled);
		$this->view->assign('headline', $headline);
		$this->view->assign('body', $body);
		$iframeEmbedCode = $link ? $this->getEmbedCode($link) : "";
		$this->view->assign('iframeembedcode', $iframeEmbedCode);
		$this->view->assign('embedurl', $this->embedUrl);
	}

	/**
	 * Get the embed code for the given url if possible
	 * and add a css class on the iframe
	 *
	 * @param string $url
	 * @param string $class
	 * @return string
	 */
	public function getEmbedCode($url, $class="dycon-video")
	{
		// Prepare url
		$url = $this->setProtocolToHttps($url);
		// Get method
		$method = $this->getMethod($url);
		if ($method !== null) {
			$embedUrl = $this->{$method}($url);
			if ($embedUrl) {
				$this->embedUrl = $embedUrl;
				$content = '
                    <iframe class="' . $class . '" src="' . $embedUrl . '" frameborder="0" allowfullscreen></iframe>
                ';
				return $content;
			}
		}
		return null;
	}

	/**
	 * Resolves if possible a method name to process the url
	 *
	 * @param string $url
	 * @return string|null
	 */
	protected function getMethod($url)
	{
		$urlInformation = @parse_url($url);
		$hostName = GeneralUtility::trimExplode('.', $urlInformation['host'], true);
		foreach ($this->mediaProvider as $provider) {
			$functionName = 'process' . ucfirst($provider);
			if (in_array($provider, $hostName) && is_callable(array($this, $functionName))) {
				return $functionName;
			}
		}
		return null;
	}

	/**
	 * Processes YouTube url
	 *
	 * @param string $url
	 * @return string
	 */
	protected function processYoutube($url)
	{
		$matches = array();
		$pattern = '%^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=))([^"&?/ ]{11})(?:.+)?$%xs';
		if (preg_match($pattern, $url, $matches)) {
			$toEmbed = $matches[1];
			$patternForAdditionalParams = '%^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=))(?:[^"&?\/ ]{11})(.+)?(?:.+)?$%xs';
			if (preg_match($patternForAdditionalParams, $url, $matches)) {
				$toEmbed .= '?' . substr($matches[1], 1);
			}
			return 'https://www.youtube.com/embed/' . $toEmbed;
		}
		return null;
	}

	/**
	 * Process YouTube short url
	 *
	 * @param string $url
	 * @return string
	 */
	protected function processYoutu($url)
	{
		return $this->processYoutube($url);
	}

	/**
	 * Processes Vimeo url
	 *
	 * @param string $url
	 * @return string
	 */
	protected function processVimeo($url)
	{
		$matches = array();
		if (preg_match('/[\\/#](\\d+)$/', $url, $matches)) {
			return 'https://player.vimeo.com/video/' . $matches[1];
		}
		return null;
	}

	/**
	 * Change every protocol to https and add it if missing
	 *
	 * @param  string $url URL
	 * @return string
	 */
	protected function setProtocolToHttps($url)
	{
		$processUrl = trim($url);
		if (substr($url, 0, 7) === 'http://') {
			$processUrl = substr($processUrl, 7);
		} elseif (substr($processUrl, 0, 8) === 'https://') {
			$processUrl = substr($processUrl, 8);
		} elseif (substr($processUrl, 0, 2) === '//') {
			$processUrl = substr($processUrl, 2);
		}
		return 'https://' . $processUrl;
	}

}
