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
class Tx_Readspeaker_Services_RenderService extends Tx_Readspeaker_Services_AbstractService implements t3lib_Singleton {

	/**
	 * @var integer
	 */
	protected $renderCount = 0;

	/**
	 * @var tslib_cObj
	 */
	public $cObj;

	/**
	 * @return Tx_Readspeaker_Services_RenderService
	 */
	public static function getInstance() {
		return t3lib_div::makeInstance('Tx_Readspeaker_Services_RenderService');
	}

	/**
	 * @param string $content
	 * @param NULL|array $configuration
	 * @return string
	 */
	public function renderFunction($content, array $configuration = NULL) {
		$this->renderCount++;

		if (empty($configuration)) {
			$configuration = $this->getTypoScriptService()->getObjectConfiguration();
		}

		$content = $this->render($configuration);
		return $content;
	}

	/**
	 * @param NULL|array $configuration
	 * @return string
	 */
	public function renderWidget(array $configuration = NULL) {
		$this->cObj = $this->getFrontend()->cObj;
		$this->renderCount++;

		if (empty($configuration)) {
			$configuration = $this->getTypoScriptService()->getObjectConfiguration();
		}

		$content = $this->render($configuration);
		return $content;
	}

	/**
	 * @param array $configuration
	 * @return string
	 */
	protected function render(array $configuration) {
		array_push(
			$this->getFrontend()->registerStack,
			$this->getFrontend()->register
		);

		$this->getFrontend()->register['readSpeakerCount'] = $this->renderCount;

		$content = $this->cObj->cObjGetSingle(
			$configuration['renderObject'],
			$configuration['renderObject.']
		);

		$this->getFrontend()->register = array_pop(
			$this->getFrontend()->registerStack
		);

		return $content;
	}

	/**
	 * @return boolean
	 */
	public function isRendered() {
		return ($this->renderCount > 0);
	}

}
?>