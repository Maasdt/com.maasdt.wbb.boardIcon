<?php
namespace wbb\system\option;
use wbb\data\board\icon\BoardIcon;
use wbb\data\board\icon\BoardIconList;
use wcf\data\option\Option;
use wcf\system\option\FontAwesomeIconSelectOptionType;

/**
 * Option type implementation for board icon selection.
 * 
 * @author	Matthias Schmidt
 * @copyright	2014-2016 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.boardIcon
 * @subpackage	system.system.option
 * @category	Burning Board
 */
class WBBBoardIconSelectOptionType extends FontAwesomeIconSelectOptionType {
	/**
	 * @see	\wcf\system\option\FontAwesomeIconSelectOptionType::getIcons()
	 */
	protected function getIcons(Option $option) {
		$icons = parent::getIcons($option);
		
		$boardIconList = new BoardIconList();
		$boardIconList->readObjects();
		
		if (count($boardIconList)) {
			if ($option->noSelection) {
				$noSelection = array_slice($icons, 0, 1, true);
			}
			
			$sortedBoardIcons = $boardIconList->getObjects();
			uasort($sortedBoardIcons, function(BoardIcon $boardIconA, BoardIcon $boardIconB) {
				return strcmp($boardIconA->getTitle(), $boardIconB->getTitle());
			});
			
			$boardIcons = [];
			foreach ($sortedBoardIcons as $boardIcon) {
				$boardIcons['wbbBoardIcon'.$boardIcon->iconID] = $boardIcon->getTitle();
			}
			
			$icons = array_merge($boardIcons, $icons);
			
			if ($option->noSelection) {
				$icons = array_merge($noSelection, $icons);
			}
		}
		
		return $icons;
	}
}
