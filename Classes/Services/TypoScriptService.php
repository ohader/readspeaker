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
class Tx_Readspeaker_Services_TypoScriptService extends Tx_Readspeaker_Services_AbstractService implements t3lib_Singleton {
	const PREFIX_Plugin = 'plugin.tx_readspeaker.';

	/**
	 * @var array
	 */
	protected $configuration;

	/**
	 * @return Tx_Readspeaker_Services_TypoScriptService
	 */
	public static function getInstance() {
		return t3lib_div::makeInstance('Tx_Readspeaker_Services_TypoScriptService');
	}

	/**
	 * Creates this object.
	 */
	public function __construct() {
		$this->setConfiguration(
			$this->getFrontend()->tmpl->setup
		);
	}

	/**
	 * @param array $configuration
	 */
	public function setConfiguration(array $configuration) {
		$this->configuration = $configuration;
	}

	/**
	 * @return array
	 */
	public function getConfiguration() {
		return $this->configuration;
	}

	/**
	 * @param string $path
	 * @param boolean $global
	 * @return NULL|string|array
	 */
	public function resolve($path, $global = FALSE) {
		if ($global === FALSE) {
			$path = self::PREFIX_Plugin . $path;
		}

		return $this->walk($path, $this->configuration);

	}

	/**
	 * @param string $path
	 * @param array $configuration
	 * @return NULL|string|array
	 */
	public function walk($path, array $configuration = NULL) {
		$parts = t3lib_div::trimExplode('.', $path, TRUE);

		if (substr($path, -1) === '.') {
			$parts[count($parts) - 1] .= '.';
		}

		foreach ($parts as $part) {
			if (isset($configuration[$part])) {
				$configuration = $configuration[$part];
			} elseif (isset($configuration[$part . '.'])) {
				$configuration = $configuration[$part . '.'];
			} else {
				$configuration = NULL;
				break;
			}
		}

		return $configuration;
	}

	/**
	 * @param string $path
	 * @param boolean $global
	 * @return string|NULL
	 */
	public function resolveFilePath($path, $global = FALSE) {
		$file = NULL;
		$value = $this->resolve($path, $global);

		if (!empty($value)) {
			$file = $this->getFrontend()->tmpl->getFileName($value);
		}

		return $file;
	}

	/**
	 * @return array
	 * @deprecated Use getWidgetConfiguration() instead
	 */
	public function getRenderObjectConfiguration() {
		return $this->getWidgetConfiguration();
	}

	/**
	 * @return array
	 */
	public function getWidgetConfiguration() {
		return array(
			'settings.' => $this->getTypoScriptService()->resolve('settings.'),
			'renderObject' => $this->getTypoScriptService()->resolve('widget.renderObject'),
			'renderObject.' => $this->getTypoScriptService()->resolve('widget.renderObject.'),
		);
	}

	/**
	 * @return array
	 */
	public function getDocumentConfiguration() {
		return array(
			'settings.' => $this->getTypoScriptService()->resolve('settings.'),
			'renderObject' => $this->getTypoScriptService()->resolve('document.renderObject'),
			'renderObject.' => $this->getTypoScriptService()->resolve('document.renderObject.'),
		);
	}
}
?>