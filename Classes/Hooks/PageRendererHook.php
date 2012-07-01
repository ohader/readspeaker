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
		if (TYPO3_MODE !== 'FE' || !$this->getPageService()->isEnabled()) {
			return;
		}

		$this->addWidget($renderer);
		$this->addResources($renderer);
	}

	protected function addWidget(t3lib_PageRenderer $renderer) {
		if ($this->getRenderService()->isRendered()) {
			return;
		}

		$renderTo = $this->getTypoScriptService()->resolve('settings.renderTo');

		if (!empty($renderTo)) {
			$matches = array();
			$pattern = '#<[a-z0-9]+\s+[^>]*?id="' . $renderTo . '"[^>]*>#mis';

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

		}
	}

	protected function getReadSpeakerWidget() {
		$readSpeakerWidget = $this->getRenderService()->renderWidget(
			$this->getRenderObjectConfiguration()
		);

		return $readSpeakerWidget;
	}

	/**
	 * @return array
	 */
	protected function getRenderObjectConfiguration() {
		return array(
			'renderObject' => $this->getTypoScriptService()->resolve('renderObject'),
			'renderObject.' => $this->getTypoScriptService()->resolve('renderObject.'),
		);
	}

	/**
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
}
?>