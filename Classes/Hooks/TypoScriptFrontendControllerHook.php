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
class Tx_Readspeaker_Hooks_TypoScriptFrontendControllerHook extends Tx_Readspeaker_Hooks_AbstractHook implements t3lib_Singleton {

	const MARKER_ReadSpeaker = '###READSPEAKER_WIDGET###';

	/**
	 * Pre-processes resources and adds ReadSpeaker JavaScript
	 *
	 * @param array $parameters
	 * @param tslib_fe $frontendController
	 */
	public function postProcessContent(array $parameters, tslib_fe $frontendController) {
		if (!$this->isEnabled()) {
			return;
		}

		$this->addWidget($frontendController);
		$this->handleDocumentLinks($frontendController);
	}

	/**
	 * Adds the ReadSpeaker widget, either to a defined CSS Id or
	 * by substituting the pre-defined marker ###READSPEAKER_WIDGET###
	 * in the markup template.
	 *
	 * @param tslib_fe $frontendController
	 */
	protected function addWidget(tslib_fe $frontendController) {
		if ($this->getRenderService()->isRendered()) {
			return;
		}

		$renderTo = $this->getTypoScriptService()->resolve('settings.renderTo');

		if (!empty($renderTo)) {
			$matches = array();
			$pattern = '#<[a-z0-9]+\s+[^>]*?id="' . preg_quote($renderTo, '#') . '"[^>]*>#mis';

			if (preg_match($pattern, $frontendController->content, $matches)) {
				$renderToObject = $matches[0];

				$content = str_replace(
					$renderToObject,
					$renderToObject . $this->getReadSpeakerWidget(),
					$frontendController->content
				);

				$frontendController->content = $content;
			}

		} elseif (strpos($frontendController->content, self::MARKER_ReadSpeaker) !== FALSE) {
			$content = str_replace(
				self::MARKER_ReadSpeaker,
				$this->getReadSpeakerWidget(),
				$frontendController->content
			);

			$frontendController->content = $content;
		}
	}

	/**
	 * @param tslib_fe $frontendController
	 * @return void
	 */
	protected function handleDocumentLinks(tslib_fe $frontendController) {
		$enableDocReader = $this->getTypoScriptService()->resolve('settings.enableDocReader');

		if (!$enableDocReader) {
			return;
		}

		$content = $frontendController->content;
		$docReaderFileExtensions = $this->getTypoScriptService()->resolve('settings.docReaderFileExtensions');

		$matches = array();
		$fileExtensions = t3lib_div::trimExplode(',', preg_quote($docReaderFileExtensions, '#'), TRUE);
		$pattern = '#<a\s+[^>]*?href="([^"]+\.(?:' . implode('|', $fileExtensions) . '))"[^>]*>#mis';

		if (preg_match_all($pattern, $content, $matches)) {
			$search = array();
			$replace = array();

			foreach ($matches[0] as $index => $link) {
				$document = $matches[1][$index];

				$documentContent = $this->getRenderService()->renderDocumentLink(
					$link,
					$this->getAbsoluteUrl($document),
					$this->getTypoScriptService()->getDocumentConfiguration()
				);

				if (!empty($documentContent)) {
					$search[] = $link;
					$replace[] = $documentContent;
				}
			}

			$content = str_replace($search, $replace, $content);
			$frontendController->content = $content;
		}
	}

	/**
	 * Gets the ReadSpeaker widget.
	 *
	 * @return string
	 */
	protected function getReadSpeakerWidget() {
		$readSpeakerWidget = $this->getRenderService()->renderWidget(
			$this->getTypoScriptService()->getWidgetConfiguration()
		);

		return $readSpeakerWidget;
	}

}
