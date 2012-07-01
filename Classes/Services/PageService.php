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
class Tx_Readspeaker_Services_PageService extends Tx_Readspeaker_Services_AbstractService implements t3lib_Singleton {
	const FIELD_Disable = 'tx_readspeaker_disable';

	/**
	 * @return Tx_Readspeaker_Services_PageService
	 */
	public static function getInstance() {
		return t3lib_div::makeInstance('Tx_Readspeaker_Services_PageService');
	}

	/**
	 * @return array
	 */
	public function get() {
		return $this->getFrontend()->page;
	}

	/**
	 * @return boolean
	 */
	public function isEnabled() {
		$currentPage = $this->get();
		$isEnabled = empty($currentPage[self::FIELD_Disable]);
		return $isEnabled;
	}
}
?>