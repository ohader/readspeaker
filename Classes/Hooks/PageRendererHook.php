<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2012 Oliver Hader <oliver.hader@typo3.org>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @author Oliver Hader <oliver.hader@typo3.org>
 * @package readspeaker
 */
class Tx_Readspeaker_Hooks_PageRendererHook implements t3lib_Singleton {
	const MARKER_ReadSpeaker = '###READSPEAKER_WIDGET###';

	/**
	 * Pre-processes resources and adds ReadSpeaker JavaScript
	 *
	 * @param array $parameters
	 * @param t3lib_PageRenderer $renderer
	 */
	public function preProcess(array $parameters, t3lib_PageRenderer $renderer) {
		if (TYPO3_MODE !== 'FE' || !$this->isEnabled()) {
			return;
		}

		$this->addWidget($renderer);
		$this->addResources($renderer);
		$this->handleDocumentLinks($renderer);
	}

	/**
	 * Determines whether ReadSpeaker is enabled.
	 *
	 * @return boolean
	 */
	protected function isEnabled() {
		return (
			$this->getPageService()->isEnabled() &&
			(bool) $this->getTypoScriptService()->resolve('settings.enable')
		);
	}

	/**
	 * Adds the ReadSpeaker widget, either to a defined CSS Id or
	 * by substituting the pre-defined marker ###READSPEAKER_WIDGET###
	 * in the markup template.
	 *
	 * @param t3lib_PageRenderer $renderer
	 */
	protected function addWidget(t3lib_PageRenderer $renderer) {
		if ($this->getRenderService()->isRendered()) {
			return;
		}

		$renderTo = $this->getTypoScriptService()->resolve('settings.renderTo');

		if (!empty($renderTo)) {
			$matches = array();
			$pattern = '#<[a-z0-9]+\s+[^>]*?id="' . preg_quote($renderTo, '#') . '"[^>]*>#mis';

			if (preg_match($pattern, $renderer->getBodyContent(), $matches)) {
				$renderToObject = $matches[0];

				$content = str_replace(
					$renderToObject,
					$renderToObject . $this->getReadSpeakerWidget(),
					$renderer->getBodyContent()
				);

				$renderer->setBodyContent($content);
			}

		} elseif (strpos($renderer->getBodyContent(), self::MARKER_ReadSpeaker) !== FALSE) {
			$content = str_replace(
				self::MARKER_ReadSpeaker,
				$this->getReadSpeakerWidget(),
				$renderer->getBodyContent()
			);

			$renderer->setBodyContent($content);
		}
	}

	/**
	 * @param t3lib_PageRenderer $renderer
	 * @return void
	 */
	protected function handleDocumentLinks(t3lib_PageRenderer $renderer) {
		$enableDocReader = $this->getTypoScriptService()->resolve('settings.enableDocReader');

		if (!$enableDocReader) {
			return;
		}

		$content = $renderer->getBodyContent();
		$matches = array();

		$docReaderUrl = $this->getTypoScriptService()->resolve('settings.docReaderUrl');
		$docReaderFileExtensions = $this->getTypoScriptService()->resolve('settings.docReaderFileExtensions');

		if (strpos($docReaderUrl, '?') === FALSE) {
			$docReaderUrl .= '?';
		}

		$fileExtensions = t3lib_div::trimExplode(',', preg_quote($docReaderFileExtensions, '#'), TRUE);
		$pattern = '#<a\s+[^>]*?href="([^"]+\.(?:' . implode('|', $fileExtensions) . '))"[^>]*>#mis';

		if (preg_match_all($pattern, $content, $matches)) {
			$search = array();
			$replace = array();

			foreach ($matches[0] as $index => $link) {
				$document = $matches[1][$index];

				$arguments = array(
					'lang' => $this->getTypoScriptService()->resolve('settings.language'),
					'voice' => $this->getTypoScriptService()->resolve('settings.voice'),
					'url' => $this->getAbsoluteUrl($document),
				);

				$attributes = array(
					'class' => 'tx-readspeaker-docreader',
					'href' => $docReaderUrl . t3lib_div::implodeArrayForUrl('', $arguments),
					'onclick' => 'window.open(this.href, \'tx-readspeaker-docreader\'); return false;',
					'title' => $this->getFrontend()->sL('LLL:EXT:readspeaker/locallang.xml:description.readContent'),
				);

				$search[] = $link;
				$replace[] = $this->createTag('a', $attributes) . $link;
			}

			$content = str_replace($search, $replace, $content);
			$renderer->setBodyContent($content);
		}
	}

	/**
	 * @param string $nodeName
	 * @param array $attributes
	 * @param string $nodeValue
	 * @return string
	 */
	protected function createTag($nodeName, array $attributes = array(), $nodeValue = '') {
		$nodeAttributes = '';
		foreach ($attributes as $attributeName => $attributeValue) {
			$nodeAttributes .= ' ' . $attributeName . '="' . htmlspecialchars($attributeValue) . '"';
		}
		return '<' . $nodeName . $nodeAttributes . '>' . $nodeValue . '</' . $nodeName . '>';
	}

	/**
	 * @param string $uri
	 * @return string
	 */
	protected function getAbsoluteUrl($uri) {
		if (t3lib_div::isValidUrl($uri)) {
			return $uri;
		}

		$siteUrl = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
		$baseUrl = $this->getTypoScriptService()->resolve('settings.baseUrl');
		if (!empty($baseUrl)) {
			$siteUrl = $baseUrl;
		}

		$uri = $siteUrl . ltrim($uri, '/');
		return $uri;
	}

	/**
	 * Gets the ReadSpeaker widget.
	 *
	 * @return string
	 */
	protected function getReadSpeakerWidget() {
		$readSpeakerWidget = $this->getRenderService()->renderWidget(
			$this->getTypoScriptService()->getObjectConfiguration()
		);

		return $readSpeakerWidget;
	}

	/**
	 * Adds required resources like JavaScript and stylesheet.
	 *
	 * @param t3lib_PageRenderer $renderer
	 */
	protected function addResources(t3lib_PageRenderer $renderer) {
		$renderer->addJsFooterFile(
			$this->getScriptUrl(),
			'text/javascript',
			FALSE
		);

		$stylesheet = $this->getTypoScriptService()->resolveFilePath('settings.stylesheet');
		if (!empty($stylesheet)) {
			$renderer->addCssFile(
				$stylesheet
			);
		}
	}

	/**
	 * @return string
	 */
 	protected function getScriptUrl() {
		 $customerId = $this->getTypoScriptService()->resolve('settings.customerId');
		 $scriptUrl = $this->getTypoScriptService()->resolve('settings.scriptUrl');
		 return str_replace('%customerId%', $customerId, $scriptUrl);
	}

	/**
	 * @return Tx_Readspeaker_Services_PageService
	 */
	protected function getPageService() {
		return Tx_Readspeaker_Services_PageService::getInstance();
	}

	/**
	 * @return Tx_Readspeaker_Services_RenderService
	 */
	protected function getRenderService() {
		return Tx_Readspeaker_Services_RenderService::getInstance();
	}

	/**
	 * @return Tx_Readspeaker_Services_TypoScriptService
	 */
	protected function getTypoScriptService() {
		return Tx_Readspeaker_Services_TypoScriptService::getInstance();
	}

	/**
	 * @return tslib_fe
	 */
	protected function getFrontend() {
		return $GLOBALS['TSFE'];
	}

}
?>