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
class Tx_Readspeaker_Common {
	/**
	 * @param	string		$table: Name of the table
	 * @param	string		$needleField: Name of the field being part of the palette to be extended
	 * @param	string		$addFields: List of fields to be added to the palette
	 * @param	string		$insertionPosition: Insert fields before (default) or after one
	 *						 of this fields (commalist with "before:", "after:" or "replace:" commands).
	 *						 Example: "before:keywords,--palette--;;4,after:description".
	 *						 Palettes must be passed like in the example no matter how the
	 *						 palette definition looks like in TCA.
	 */
	public static function addToAllPalettesContainingField($table, $needleField, $addFields, $insertionPosition = '') {
		$palettes = array();

		t3lib_div::loadTCA($table);
		foreach ($GLOBALS['TCA'][$table]['palettes'] as $paletteKey => $paletteData) {
			if (!empty($paletteData['showitem'])) {
				$elements = t3lib_div::trimExplode(',', $paletteData['showitem'], TRUE);
				foreach ($elements as $element) {
					list($field) = t3lib_div::trimExplode(';', $element, TRUE, 2);
					if (!empty($field) && $field === $needleField) {
						$palettes[] = $paletteKey;
						break;
					}
				}
			}
		}

		foreach ($palettes as $paletteKey) {
			t3lib_extMgm::addFieldsToPalette($table, $paletteKey, $addFields, $insertionPosition);
		}
	}
}
?>