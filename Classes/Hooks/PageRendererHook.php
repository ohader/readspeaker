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
class Tx_Readspeaker_Hooks_PageRendererHook extends Tx_Readspeaker_Hooks_AbstractHook implements t3lib_Singleton {

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

		$this->addResources($renderer);
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

}
