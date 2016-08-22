<?php
namespace wbb\system\event\listener;
use wbb\system\board\BoardIconHandler;
use wcf\system\event\IEventListener;

/**
 * Rewrites the board icons when editing the board options.
 * 
 * @author	Matthias Schmidt
 * @copyright	2014-2016 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.boardIcon
 * @subpackage	system.board
 * @category	Community Framework
 */
class OptionFormBoardIconListener implements IEventListener {
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($eventObj->categoryName == 'board') {
			BoardIconHandler::getInstance()->writeStyleFile();
		}
	}
}
