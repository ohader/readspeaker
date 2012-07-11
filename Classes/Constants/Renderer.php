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
class Tx_Readspeaker_Constants_Renderer implements t3lib_Singleton {
	/**
	 * @var array
	 */
	protected $buttons = array(
		'tx-readspeaker-icon-basic' => 'EXT:readspeaker/Resources/Public/Images/basic1-117.png',
		'tx-readspeaker-icon-sound' => 'EXT:readspeaker/Resources/Public/Images/sound_high_icon.png',
		'tx-readspeaker-icon-default-12' => 'EXT:readspeaker/Resources/Public/Images/icon_12px_black.gif',
		'tx-readspeaker-icon-default-16' => 'EXT:readspeaker/Resources/Public/Images/icon_16px_black.gif',
	);

	/**
	 * Renders the button class selector.
	 *
	 * @param array $parameters
	 * @param t3lib_tsparser_ext $parent
	 * @return string
	 */
	public function renderButtonClassSelector(array $parameters, t3lib_tsparser_ext $parent) {
		$content = '';
		$defaultStyle = 'line-height: 24px; margin: 0 15px 0 5px; padding: 5px 24px 5px 0; background-position: right center; background-repeat: no-repeat;';

		foreach ($this->buttons as $buttonClass => $buttonUrl) {
			$checked = ($buttonClass === $parameters['fieldValue'] ? ' checked="checked"' : '');
			$style = $defaultStyle . ' background-image: url(\'' . $this->getPublicUrl($buttonUrl) . '\');';

			$content .= '<span style="' . $style . '">';
			$content .= '<input type="radio" value="' . htmlspecialchars($buttonClass) . '" name="' . htmlspecialchars($parameters['fieldName']) . '"' . $checked . '/>';
			$content .= '</span>';
		}

		return $content;
	}

	/**
	 * Gets public URL for a path.
	 *
	 * @param string $path
	 * @return string
	 */
	protected function getPublicUrl($path) {
		$publicUrl = '';
		$absoluteFileName = t3lib_div::getFileAbsFileName($path);

		if (strpos($absoluteFileName, PATH_site) === 0) {
			$publicUrl = substr(
				$absoluteFileName,
				strlen(PATH_site)
			);
			$publicUrl = '/' . ltrim($publicUrl, '/');
		}

		return $publicUrl;
	}
}
?>